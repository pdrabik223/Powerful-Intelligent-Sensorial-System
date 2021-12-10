import cv2
import numpy as np
import matplotlib.pyplot as plt


def FilterGreen(color):

    r_c = color[2] / 255
    g_c = color[1] / 255
    b_c = color[0] / 255

    c_max = max(r_c, g_c)
    c_max = max(c_max, b_c)

    c_min = min(r_c, g_c)
    c_min = min(c_min, b_c)

    delta = c_max - c_min

    v = c_max

    if v < 0.1:
        return False

    s = 0
    if c_max != 0:
        s = delta / c_max

    if s < 0.1:
        return False

    h = 0.
    if c_max == r_c:

        h = 60 * (((g_c - b_c) / delta) % 6)

    elif c_max == g_c:

        h = 60 * (((b_c - r_c) / delta) + 2)

    elif c_max == b_c:

        h = 60 * (((r_c - g_c) / delta) + 4)

    if(h < 90 or h > 152):
        return False
    return True


def Analisis(frame):

    height, width, _ = frame.shape

    gray_green_img = np.zeros(frame.shape, np.uint8)
    gray_green_img.fill(0)

    for i in range(height):
        for j in range(width):
            if FilterGreen(frame[i, j]):
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


def display_mateusz(frame, activation_grid,  no_frame):

    plt.figure(figsize=(15, 15))
    plt.subplot(2, 3, (1, 3))

    plt.imshow(frame)
    plt.title('Flower')

    plt.subplot(2, 3, 4)
    plt.title('Activated Cells')
    plt.imshow(activation_grid)

    log_10 = np.log10(activation_grid)
    log_10 = np.add(log_10, 1)
    log_10 = np.clip(log_10, 0, None)

    plt.subplot(2, 3, 5)
    plt.title('Activated Cells log 10')
    plt.imshow(log_10)

    def bool_me(x):
        if(x < 0.3):
            return 0
        else:
            return 1

    bolean = np.vectorize(bool_me)

    plt.subplot(2, 3, 6)
    plt.title('Bolean Activated Cells')
    plt.imshow(bolean(log_10))

    plt.savefig('spotkanie2/frame_' + str(no_frame) + '.png')


video = cv2.VideoCapture('spotkanie2/Time_Lapse_of_Sunflower.mp4')
# window = cv2.namedWindow('original video', cv2.WINDOW_AUTOSIZE)
currentframe = 0

fig = plt.figure()

video.set(1, 100)

while(True):
   # read image
    ret, frame = video.read()

    if ret:
        currentframe += 1
    else:
        break

    if currentframe == 10:
        break

    frame = cv2.resize(frame, (0, 0), fx=0.5, fy=0.5)

    frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

    # display image

    # display every frame, and every stage of analisys
    # name = 'frame' + str(currentframe) + '.jpg'
    # cv2.imshow('original video', frame)

    print(currentframe)
    display_mateusz(frame, Analisis(frame), no_frame=currentframe)

   # wait for press key
    # cv2.waitKey(0)


# cv2.destroyAllWindows()
