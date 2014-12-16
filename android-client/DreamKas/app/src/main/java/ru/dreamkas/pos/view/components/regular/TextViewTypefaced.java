package ru.dreamkas.pos.view.components.regular;

import android.content.Context;
import android.content.res.ColorStateList;
import android.content.res.TypedArray;
import android.graphics.Color;
import android.graphics.Typeface;
import android.support.v7.widget.Toolbar;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.Spanned;
import android.text.style.ScaleXSpan;
import android.util.AttributeSet;
import android.util.Pair;
import android.view.Gravity;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;

public class TextViewTypefaced extends TextView {
    final public String REGULAR_FONT = "Roboto-Regular.ttf";
    final public String MEDIUM_FONT = "Roboto-Medium.ttf";
    private int mDkpWidth;
    private int mDkpHeight;


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
        //TypedArray a = context.obtainStyledAttributes(attrs, R.styleable.text_view);

        int[] textSizeAttr = new int[] {
                android.R.attr.textSize,
                R.styleable.text_view_font_style,
                R.styleable.text_view_font_opacity,
                R.styleable.text_view_font_kerning,
                android.R.attr.layout_width,
                android.R.attr.layout_height
        };

        TypedArray a = context.obtainStyledAttributes(attrs, textSizeAttr);

        ///set font
        Pair<String, Float> font = getFontFromAttrs(a);
        setFont(font.first, font.second);

        int textSize = a.getDimensionPixelSize(0, -1);

        if(textSize != -1){
            setTextSize(textSize * DreamkasApp.getDkp());
        }else {
            setTextSize(14 * DreamkasApp.getDkp());
        }

        Float kerning = a.getFloat(3, -1);

        if (kerning != -1) {
            applySpacing(kerning);
        }


        int width = a.getInteger(4, ViewGroup.LayoutParams.WRAP_CONTENT);

        if(width !=  ViewGroup.LayoutParams.MATCH_PARENT && width !=  ViewGroup.LayoutParams.WRAP_CONTENT){
            width = DreamkasApp.toDkpInPixels(width);
        }

        int height = a.getInteger(5, ViewGroup.LayoutParams.WRAP_CONTENT);
        if(height !=  ViewGroup.LayoutParams.MATCH_PARENT && height !=  ViewGroup.LayoutParams.WRAP_CONTENT){
            height = DreamkasApp.toDkpInPixels(height);
        }

        mDkpWidth = width;
        mDkpHeight = height;

        a.recycle();
    }

    private void applySpacing(float spacing) {

        spacing = -1;
        CharSequence originalText = getText();

        if (this == null || originalText == null) return;
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

        setText(finalText, TextView.BufferType.SPANNABLE);
    }

    @Override
    protected void onAttachedToWindow(){
        super.onAttachedToWindow();

        if(!isInEditMode()) {
            if(getLayoutParams() instanceof LinearLayout.LayoutParams){
                LinearLayout.LayoutParams lpOld = (LinearLayout.LayoutParams)getLayoutParams();
                LinearLayout.LayoutParams lpNew = new LinearLayout.LayoutParams(mDkpWidth, mDkpHeight);
                lpNew.gravity = lpOld.gravity;
                lpNew.setMargins(DreamkasApp.toDkpInPixels(lpOld.leftMargin), DreamkasApp.toDkpInPixels(lpOld.topMargin), DreamkasApp.toDkpInPixels(lpOld.rightMargin), DreamkasApp.toDkpInPixels(lpOld.bottomMargin));
                setLayoutParams(lpNew);
            }else if(getLayoutParams() instanceof Toolbar.LayoutParams){
                Toolbar.LayoutParams lpOld = (Toolbar.LayoutParams)getLayoutParams();
                Toolbar.LayoutParams lpNew = new Toolbar.LayoutParams(lpOld);

                lpNew.width = mDkpWidth;
                lpNew.height = mDkpHeight;
                lpNew.gravity = lpOld.gravity;

                lpNew.setMargins(DreamkasApp.toDkpInPixels(lpOld.leftMargin), DreamkasApp.toDkpInPixels(lpOld.topMargin), DreamkasApp.toDkpInPixels(lpOld.rightMargin), DreamkasApp.toDkpInPixels(lpOld.bottomMargin));

                setLayoutParams(lpNew);
            }
        }
    }

    private Pair<String, Float> getFontFromAttrs(TypedArray typedArray) {
        CharSequence s = typedArray.getString(1);
        String font = REGULAR_FONT;
        if (s != null) {
            if(s.equals("medium")){
                font = MEDIUM_FONT;
            }
        }

        Float opacity = 0.87f;
        Float i = typedArray.getFloat(2, -1);
        if (i != -1) {
            opacity = i;
        }

        return new Pair<String, Float>(font, opacity);
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
