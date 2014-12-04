package ru.dreamkas.pos.view.components.regular;

import android.content.Context;
import android.content.res.ColorStateList;
import android.content.res.TypedArray;
import android.graphics.Color;
import android.graphics.Typeface;
import android.util.AttributeSet;
import android.widget.TextView;

import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;

public class TextViewTypefaced extends TextView {
    final public String REGULAR_FONT = "Roboto-Regular.ttf";
    final public String MEDIUM_FONT = "Roboto-Medium.ttf";

    public TextViewTypefaced(Context context) {
        super(context);
        setFont(REGULAR_FONT, 0.87f);
    }

    private void setFont(String font, float opacity) {
        try{
            Typeface typeface = Typeface.createFromAsset(DreamkasApp.getContext().getAssets(), font);
            setTypeface(typeface);

            String hexColor = String.format("%06X", (0xFFFFFF & getCurrentTextColor()));
            int color = (int)Long.parseLong(hexColor, 16);
            int r = (color >> 16) & 0xFF;
            int g = (color >> 8) & 0xFF;
            int b = (color >> 0) & 0xFF;

            setTextColor(Color.argb(Math.round(255*opacity), r, g, b));

        }catch (NullPointerException ex){

        }
    }

    public TextViewTypefaced(Context context, AttributeSet attrs) {
        super(context, attrs);
        setAttributes(context, attrs);
    }

    public TextViewTypefaced(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        setAttributes(context, attrs);
    }

    private void setAttributes(Context context, AttributeSet attrs) {
        TypedArray a = context.obtainStyledAttributes(attrs, R.styleable.text_view);
        CharSequence s = a.getString(R.styleable.text_view_font_style);

        String font = REGULAR_FONT;
        if (s != null) {
            if(s.equals("medium")){
                font = MEDIUM_FONT;
            }
        }

        Float opacity = 0.87f;
        Float i = a.getFloat(R.styleable.text_view_font_opacity, -1);
        if (i != -1) {
            opacity = i;
        }
        setFont(font, opacity);

        a.recycle();
    }

   /* private float spacing = Spacing.NORMAL;
    private CharSequence originalText = "";

    public float getSpacing() {
        return this.spacing;
    }

    public void setSpacing(float spacing) {
        this.spacing = spacing;
        applySpacing();
    }

    @Override
    public void setText(CharSequence text, BufferType type) {
        originalText = text;
        applySpacing();
    }

    @Override
    public CharSequence getText() {
        return originalText;
    }

    private void applySpacing() {
        if (this == null || this.originalText == null) return;
        StringBuilder builder = new StringBuilder();
        for(int i = 0; i < originalText.length(); i++) {
            builder.append(originalText.charAt(i));
            if(i+1 < originalText.length()) {
                builder.append("\u00A0");
            }
        }
        SpannableString finalText = new SpannableString(builder.toString());
        if(builder.toString().length() > 1) {
            for(int i = 1; i < builder.toString().length(); i+=2) {
                finalText.setSpan(new ScaleXSpan((spacing+1)/10), i, i+1, Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);
            }
        }
        super.setText(finalText, BufferType.SPANNABLE);
    }

    public class Spacing {
        public final static float NORMAL = 0;
    }*/
}
