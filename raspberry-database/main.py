from os.path import exists
import numpy as np
import csv
import datetime
import time
import argparse
import mysql.connector
import schedule
from dataclasses import dataclass


# todo print info about connected devices, database connection state, and current aplication job
# todo intake database all database params
# todo connect real sensors not those dummy ones

@dataclass
class Measurement:
    name: str
    timestamp: datetime.datetime
    value: any


def measure_temperature() -> Measurement:
    return Measurement(
        name="temp",
        timestamp=datetime.datetime.now(),
        value=np.random.normal(22.2, 0.6, (1))[0])


def measure_light_level() -> Measurement:
    return Measurement("light_level", datetime.datetime.now(), np.random.normal(50, 10, 1)[0])


# TODO: Add changing destination path
def append_to_local_csv(measurement: Measurement) -> None:
    file_name = f"{datetime.datetime.now().strftime('%Y_%m_%d')}_{measurement.name}.csv"

    writer = None
    if not exists(file_name):
        file = open(file_name, 'w', newline='')
        writer = csv.writer(file, "excel")
        writer.writerow(["date_time ,", measurement.name])
    else:
        file = open(file_name, 'a', newline='')
        writer = csv.writer(file, "excel")
    writer.writerow([measurement.timestamp, measurement.value])
    file.close()


def append_to_database(connection, measurement: Measurement):
    cursor = connection.cursor(buffered=True)

    query = f"INSERT INTO {measurement.name} (date, {measurement.name}) VALUES (TIMESTAMP'{measurement.timestamp.strftime('%Y-%m-%d %H:%M:%S')}', {round(measurement.value, 3)})"
    cursor.execute(query)

    cursor.close()
    connection.commit()


def collect_factory(measurment_function):
    def measurment_func():
        result = measurment_function()
        append_to_local_csv(result)
        append_to_database(SQL_CONNECTION, result)

    return measurment_func


# TODO: Make this not be global
SQL_CONNECTION = None

def main():
    parser = argparse.ArgumentParser(description='Periodically measure temperature.')
    parser.add_argument('--database-ip', '-i', type=str, help='database ip v4', default='127.0.0.1')
    parser.add_argument('--debug', '-d', action='store_true',
                        help='Debug mode will collect ten samples in 10 second periods',
                        default=False)
    args = parser.parse_args()

    global SQL_CONNECTION
    SQL_CONNECTION = mysql.connector.connect(user='root',
                                             password='pssd123',
                                             host=args.database_ip,
                                             database='measurements')

    if args.debug:
        for _ in range(10):
            collect_factory(measure_temperature)()
            collect_factory(measure_light_level)()
            time.sleep(1)
        return

    # For more intervals: https://schedule.readthedocs.io/en/stable/
    schedule.every().minute.do(collect_factory(measure_temperature))
    schedule.every(0.5).hours.do(collect_factory(measure_light_level))

    while True:
        schedule.run_pending()
        time.sleep(1)


if __name__ == '__main__':
    main()
