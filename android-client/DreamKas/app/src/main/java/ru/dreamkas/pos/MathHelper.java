package ru.dreamkas.pos;

import org.apache.commons.lang3.math.Fraction;

public class MathHelper {
    public static int gcm(int a, int b) {
        return b == 0 ? a : gcm(b, a % b);
    }

    public static Fraction asFraction(int a, int b) {
        int gcm = gcm(a, b);
        return Fraction.getFraction((a / gcm),(b / gcm));
    }
}
