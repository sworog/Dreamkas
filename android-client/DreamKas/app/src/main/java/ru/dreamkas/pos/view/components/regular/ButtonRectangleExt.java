package ru.dreamkas.pos.view.components.regular;

import android.content.Context;
import android.content.res.TypedArray;
import android.graphics.Typeface;
import android.util.AttributeSet;
import android.widget.TextView;

import com.gc.materialdesign.views.ButtonRectangle;

import ru.dreamkas.pos.DreamkasApp;

public class ButtonRectangleExt extends ButtonRectangle {
    final public String MEDIUM_FONT = "Roboto-Medium.ttf";

    public ButtonRectangleExt(Context context, AttributeSet attrs) {
        super(context, attrs);

        TextView buttonText = getTextView();
        Typeface typeface = Typeface.createFromAsset(DreamkasApp.getContext().getAssets(), MEDIUM_FONT);
        buttonText.setTypeface(typeface);

        this.setAttrs(getContext(), attrs);
    }

    private void setAttrs(Context context, AttributeSet attrs) {
        int[] textSizeAttr = new int[] { android.R.attr.textSize };
        TypedArray a = context.obtainStyledAttributes(attrs, textSizeAttr);
        int textSize = a.getDimensionPixelSize(0, -1);

        if(textSize != -1){
            getTextView().setTextSize(textSize);
        }else {
            getTextView().setTextSize(14);
        }
        a.recycle();
    }
}
