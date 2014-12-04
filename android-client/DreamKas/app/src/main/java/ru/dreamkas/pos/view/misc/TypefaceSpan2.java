package ru.dreamkas.pos.view.misc;

import android.graphics.Paint;
import android.graphics.Typeface;
import android.text.TextPaint;
import android.text.style.MetricAffectingSpan;

public class TypefaceSpan2 extends MetricAffectingSpan {
    private final Typeface mTypeface;
    private final int mTypefaceStyle;

    public TypefaceSpan2(Typeface typeface, int typefaceStyle) {
        mTypefaceStyle = typefaceStyle;
        mTypeface = typeface;
    }

    @Override
    public void updateDrawState(TextPaint ds) {
        apply(ds, mTypeface, mTypefaceStyle);
    }

    @Override
    public void updateMeasureState(TextPaint paint) {
        apply(paint, mTypeface, mTypefaceStyle);
    }

    private static void apply(Paint paint, Typeface tf, int mTypefaceStyle) {
        int oldStyle;

        Typeface old = paint.getTypeface();
        if (old == null) {
            oldStyle = 0;
        } else {
            oldStyle = old.getStyle();
        }

        int fake = oldStyle & ~tf.getStyle();

        if ((fake & Typeface.BOLD) != 0) {
            paint.setFakeBoldText(true);
        }

        if ((fake & Typeface.ITALIC) != 0) {
            paint.setTextSkewX(-0.25f);
        }

        if(mTypefaceStyle == Typeface.BOLD){
            paint.setFakeBoldText(true);
        }

        paint.setTypeface(tf);
    }
}
