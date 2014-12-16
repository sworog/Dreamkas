package ru.dreamkas.pos.view.components.regular;

import android.content.Context;
import android.content.res.TypedArray;
import android.graphics.Typeface;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ScaleXSpan;
import android.util.AttributeSet;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.gc.materialdesign.views.ButtonRectangle;

import ru.dreamkas.pos.DreamkasApp;

public class ButtonRectangleExt extends ButtonRectangle {
    final public String MEDIUM_FONT = "Roboto-Medium.ttf";

    private int mDkpWidth;
    private int mDkpHeight;

    public ButtonRectangleExt(Context context, AttributeSet attrs) {
        super(context, attrs);

        if(DreamkasApp.getContext() != null && DreamkasApp.getContext().getAssets() != null && getTextView() != null){
            TextView buttonText = getTextView();
            Typeface typeface = Typeface.createFromAsset(DreamkasApp.getContext().getAssets(), MEDIUM_FONT);
            buttonText.setTypeface(typeface);
        }
        if(!isInEditMode()){
            this.setAttrs(getContext(), attrs);
        }
    }

    private void setAttrs(Context context, AttributeSet attrs) {

        int[] textSizeAttr = new int[] {
                android.R.attr.textSize,
                android.R.attr.layout_width,
                android.R.attr.layout_height
        };
        TypedArray a = context.obtainStyledAttributes(attrs, textSizeAttr);
        int textSize = a.getDimensionPixelSize(0, -1);

        if(textSize != -1){
            getTextView().setTextSize(textSize * DreamkasApp.getDkp());
        }else {
            getTextView().setTextSize(14 * DreamkasApp.getDkp());
        }

        int width = a.getLayoutDimension(1, "unknown");

        if(width !=  ViewGroup.LayoutParams.MATCH_PARENT && width !=  ViewGroup.LayoutParams.WRAP_CONTENT){
            width = DreamkasApp.toDkpInPixels(width);
        }

        int height = a.getLayoutDimension(2, "unknown");
        if(height !=  ViewGroup.LayoutParams.MATCH_PARENT && height !=  ViewGroup.LayoutParams.WRAP_CONTENT){
            height = DreamkasApp.toDkpInPixels(height);
        }

        mDkpWidth = width;
        mDkpHeight = height;

        a.recycle();
        applySpacing();
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
            }else if(getLayoutParams() instanceof RelativeLayout.LayoutParams){
                RelativeLayout.LayoutParams lpOld = (RelativeLayout.LayoutParams)getLayoutParams();
                RelativeLayout.LayoutParams lpNew = new RelativeLayout.LayoutParams(mDkpWidth, mDkpHeight);

                int[] rules = ((LayoutParams) getLayoutParams()).getRules();

                for(int i = 0; i < rules.length; i += 1) {
                    if(rules[i] == -1){
                        lpNew.addRule(i);
                    }
                }

                //справа отступ больше дабы компенсировать отступы у символов букв в шривте слева
                setPadding(DreamkasApp.toDkpInPixels(8),0,DreamkasApp.toDkpInPixels(10),0);

                lpNew.setMargins(DreamkasApp.toDkpInPixels(lpOld.leftMargin), DreamkasApp.toDkpInPixels(lpOld.topMargin), DreamkasApp.toDkpInPixels(lpOld.rightMargin), DreamkasApp.toDkpInPixels(lpOld.bottomMargin));
                setLayoutParams(lpNew);
            }
        }
    }

    private void applySpacing() {
        float spacing = 0.25f;

        CharSequence originalText = getTextView().getText();

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

        getTextView().setText(finalText, TextView.BufferType.SPANNABLE);
    }

}
