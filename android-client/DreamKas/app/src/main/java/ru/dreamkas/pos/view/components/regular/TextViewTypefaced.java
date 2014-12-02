package ru.dreamkas.pos.view.components.regular;

import android.content.Context;
import android.graphics.Typeface;
import android.util.AttributeSet;
import android.widget.TextView;

import ru.dreamkas.pos.DreamkasApp;

public class TextViewTypefaced extends TextView {
    final public String FONT = "Roboto-Regular.ttf";

    public TextViewTypefaced(Context context) {
        super(context);
        setFont();
    }

    private void setFont() {
        Typeface typeface = Typeface.createFromAsset(DreamkasApp.getContext().getAssets(), FONT);
        setTypeface(typeface);
    }

    public TextViewTypefaced(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public TextViewTypefaced(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
    }
}
