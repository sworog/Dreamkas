package ru.dreamkas.pos.view.misc;

import android.graphics.Typeface;
import android.text.SpannableStringBuilder;
import android.text.style.AbsoluteSizeSpan;

import ru.dreamkas.pos.DreamkasApp;

public class StringDecorator {
    final private static Typeface roubleSupportedTypeface = Typeface.createFromAsset(DreamkasApp.getContext().getAssets(), "rouble2.ttf");
    public final static char RUBLE_CODE = (char)0x20BD;

    public static SpannableStringBuilder buildStringWithRubleSymbol(String format, Object... args){
        return buildStringWithRubleSymbol(false, format, args);
    }

    public static SpannableStringBuilder buildStringWithRubleSymbol(int textSize, String format, Object... args){
        String str = String.format(format, args);
        return typeRubleSymbol(str, Typeface.NORMAL, textSize);
    }

    public static SpannableStringBuilder buildStringWithRubleSymbol(boolean bold, String format, Object... args){
        String str = String.format(format, args);
        if(bold){
            return typeRubleSymbol(str, Typeface.BOLD);
        }else {
            return typeRubleSymbol(str, Typeface.NORMAL);
        }
    }

    private static SpannableStringBuilder typeRubleSymbol(String str, int typefaceStyle){
        return typeRubleSymbol(str, typefaceStyle, 16);
    }

    private static SpannableStringBuilder typeRubleSymbol(String str, int typefaceStyle, int textSize){
        SpannableStringBuilder resultSpan = new SpannableStringBuilder(str);
        for (int i = 0; i < resultSpan.length(); i++) {
            if (resultSpan.charAt(i) == RUBLE_CODE) {
                TypefaceSpan2 roubleTypefaceSpan = new TypefaceSpan2(roubleSupportedTypeface, typefaceStyle);

                resultSpan.setSpan(new AbsoluteSizeSpan(textSize), i, i + 1, 0);
                resultSpan.setSpan(roubleTypefaceSpan, i, i + 1, 0);
            }
        }
        return resultSpan;
    }
}
