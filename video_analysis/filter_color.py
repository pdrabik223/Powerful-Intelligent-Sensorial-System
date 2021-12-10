
class FilterColor:
    # parameters come in pairs,
    # only values in range <low,high> will pass the tests
    # passed h values must fall in range <0,365>
    # passed s,v values must fall in range <0,255>
    def __init__(self, name, h_low, h_high, s_low, s_high, v_low, v_high):
        self.name = name
        self.h_low = h_low
        self.h_high = h_high
        self.s_low = s_low
        self.s_high = s_high
        self.v_low = v_low
        self.v_high = v_high

    def __str__(self):
        return "name: %s h <%s,%s>, s <%s, %s>, v <%s, %s>" % (self.name,
                                                               self.h_low, self.h_high,
                                                               self.s_low, self.s_high,
                                                               self.v_low, self.v_high)

    def Filter(self, color):
        # print(self)
        r_c = color[2] / 255
        g_c = color[1] / 255
        b_c = color[0] / 255

        c_max = max(r_c, g_c)
        c_max = max(c_max, b_c)

        c_min = min(r_c, g_c)
        c_min = min(c_min, b_c)

        delta = c_max - c_min

        v = c_max

        if v < self.v_low or v > self.v_high:
            return False

        s = 0
        if c_max != 0:
            s = delta / c_max

        if s < self.s_low or s > self.s_high:
            return False

        h = 0.
        if c_max == r_c:

            h = 60 * (((g_c - b_c) / delta) % 6)

        elif c_max == g_c:

            h = 60 * (((b_c - r_c) / delta) + 2)

        elif c_max == b_c:

            h = 60 * (((r_c - g_c) / delta) + 4)

        if(h < self.h_low or h > self.h_high):
            return False
        return True
