import cv2
import numpy as np
import time

from filter_color import FilterColor


def Analisis(rules, frame):
    height, width, _ = frame.shape

    gray_green_img = np.zeros(frame.shape, np.uint8)
    gray_green_img.fill(0)

    for i in range(height):
        for j in range(width):
            if rules.Filter(frame[i, j]):
                gray_green_img[i, j] = frame[i, j]

    no_x_cells = 50
    no_y_cells = 50

    cell_x_size = width // no_x_cells
    cell_y_size = height // no_y_cells

    if cell_x_size * no_x_cells != width:
        cell_x_size += 1
    if cell_y_size * no_y_cells != width:
        cell_y_size += 1

    activation_grid = np.zeros((no_x_cells, no_y_cells))

    for i in range(height):
        for j in range(width):
            activation_grid[i // cell_y_size,
                            j // cell_x_size] += gray_green_img[i, j][2]

    max_value = np.max(activation_grid)
    # normalize data:
    activation_grid = np.divide(activation_grid, max_value)

    return activation_grid


def Log10Amplification(activation_grid):
    log_10 = np.log10(activation_grid)
    log_10 = np.add(log_10, 1)
    log_10 = np.clip(log_10, 0, None)
    return log_10


def BooleanCut(activation_grid):
    def bool_me(x):
        if (x < 0.3):
            return 0
        else:
            return 1

    bolean_output = np.vectorize(bool_me)
    return bolean_output(activation_grid)


def Sum(activation_grid) -> int:
    sum = 0
    for i in activation_grid:
        sum += i
    return sum


color_to_filter = FilterColor('Green', 90, 140, 0.24, 1, 0.24, 1)
currentframe = 0

sleep_time: int
sleep_time = 1

while (True):
    video = cv2.VideoCapture(0)

    ret, frame = video.read()
    frame = cv2.resize(frame, (0, 0), fx=0.5, fy=0.5)
    frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    print(currentframe)

    cv2.imwrite(str(currentframe) + ".jpg", frame)
    video.release()

    if ret:
        currentframe += 1
    else:
        break

    green_sum = Sum(BooleanCut(Log10Amplification(Analisis(color_to_filter, frame))))

    file = open('camera_output.txt', 'a')
    file.write(str(frame) + " " + str(green_sum) + "\n")
    file.close()
    time.sleep(sleep_time)
