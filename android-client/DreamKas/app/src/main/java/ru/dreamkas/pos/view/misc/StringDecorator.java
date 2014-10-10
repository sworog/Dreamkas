package ru.dreamkas.pos.view.misc;

import android.graphics.Typeface;
import android.text.SpannableStringBuilder;

import ru.dreamkas.pos.DreamkasApp;

public class StringDecorator {
    final private static Typeface roubleSupportedTypeface = Typeface.createFromAsset(DreamkasApp.getContext().getAssets(), "rouble2.ttf");
    public final static char RUBLE_CODE = (char)0x20BD;

    public static SpannableStringBuilder addRubleSymbol(String str){
        SpannableStringBuilder resultSpan = new SpannableStringBuilder(str);
        for (int i = 0; i < resultSpan.length(); i++) {
            if (resultSpan.charAt(i) == RUBLE_CODE) {
                TypefaceSpan2 roubleTypefaceSpan = new TypefaceSpan2(roubleSupportedTypeface);
                resultSpan.setSpan(roubleTypefaceSpan, i, i + 1, 0);
            }
        }
        return resultSpan;
    }
}
