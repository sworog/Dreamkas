package ru.dreamkas.pos.view.components;

import android.content.Context;
import android.content.res.TypedArray;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Spanned;
import android.util.AttributeSet;
import android.widget.EditText;

import com.rengwuxian.materialedittext.MaterialEditText;

import org.apache.commons.lang3.StringUtils;

import java.math.BigDecimal;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.ParseException;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;

public class NumericEditText extends MaterialEditText {
    private DecimalFormat mDecimalFormat;
    private Integer mMaximumFractionDigits = 1;
    private Integer mMinimumFractionDigits = 1;
    private BigDecimal mMaxValue;

    private static boolean[] mIsValidChar;
    static {
        mIsValidChar = new boolean[Character.MAX_VALUE + 1];
        for (char c : new char[]{'0','1','2','3','4','5','6','7','8','9','.',','}) {
            mIsValidChar[c] = true;
        }
    }

    public void setAllowEmptyValue(boolean mAllowEmptyValue) {
        this.mAllowEmptyValue = mAllowEmptyValue;
    }

    private boolean mAllowEmptyValue = false;
    private InputFilter mInputFilter = new InputFilter() {
        public CharSequence filter(CharSequence source, int start, int end, Spanned dest, int dstart, int dend) {
            String currentValue = dest.toString();
            String separator = String.valueOf(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator());

            String delta = source.toString().substring(start, end);
            String targetValue = currentValue.substring(0, dstart) + delta + currentValue.substring(dend, currentValue.length());

            String result = null;

            for (char c : delta.toCharArray()) {
                if (!mIsValidChar[c]) {
                    result = "";
                }
            }

            if(result == null){
                int commaCount = StringUtils.countMatches(targetValue, String.valueOf(Constants.COMMA));
                int dotCount = StringUtils.countMatches(targetValue, String.valueOf(Constants.DOT));

                if((targetValue.length() == 1 && (dotCount > 0 || commaCount > 0) ) || (dotCount > 0 && commaCount > 0) || (dotCount > 1) || (commaCount > 1)){
                    result = "";
                }
            }

            if(result == null){
                String[] valueParts = targetValue.split("\\" + separator);

                if(valueParts.length > 1){
                    String lastBlock = valueParts[valueParts.length - 1];
                    if(lastBlock.length() > mMaximumFractionDigits){
                        result = "";
                    }
                }
            }

            if(result == ""){
                targetValue = currentValue.substring(0, dstart) + currentValue.substring(dend, currentValue.length());
                if(targetValue.length() == 0 && !mAllowEmptyValue){
                    setError(DreamkasApp.getResourceString(R.string.msg_error_empty_value));
                }
            }else {
                String str = getText().toString();

                char newSeparator = getCurrentSeparator(str);
                if(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator() != newSeparator){
                    changeFormat(newSeparator);
                }

                if(mMaxValue != null){
                    try {
                        BigDecimal bd = (BigDecimal)mDecimalFormat.parse(str);
                        if(bd.compareTo(mMaxValue) == 1){
                            result = "";
                        }
                    } catch (ParseException e) {
                        e.printStackTrace();
                    }
                }
            }

            return result;
        }
    };

    public NumericEditText(Context context) {
        super(context);
        init();
    }

    public NumericEditText(Context context, AttributeSet attrs) {
        super(context, attrs);
        setAttributes(context, attrs);
        init();
    }

    public NumericEditText(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        setAttributes(context, attrs);
        init();
    }

    public void setMinMaxFractionDigits(Integer min, Integer max){
        mMinimumFractionDigits = min;
        mMaximumFractionDigits = max;
        changeFormat(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator());
    }

    private void setAttributes(Context context, AttributeSet attrs) {
        TypedArray a = context.obtainStyledAttributes(attrs, R.styleable.numeric_edit_text);
        int minDigits = a.getInteger(R.styleable.numeric_edit_text_minimum_fraction_digits, -1);
        int maxDigits = a.getInteger(R.styleable.numeric_edit_text_maximum_fraction_digits, -1);

        int maxValue = a.getInteger(R.styleable.numeric_edit_text_maximum_value, -1);

        if (maxValue != -1) {
            mMaxValue = new BigDecimal(maxValue);
        }

        if (minDigits != -1) {
            mMinimumFractionDigits = minDigits;
        }
        if (maxDigits != -1) {
            mMaximumFractionDigits= maxDigits;
        }
        a.recycle();
    }

    private void init(){
        //setRawInputType(InputType.TYPE_NUMBER_FLAG_DECIMAL);

        setRawInputType(InputType.TYPE_CLASS_NUMBER | InputType.TYPE_NUMBER_FLAG_DECIMAL);

        changeFormat(Constants.COMMA);
        setFilters(new InputFilter[]{mInputFilter});
    }

    public BigDecimal getValue(){
        try
        {
            String str = getText().toString();

            char newSeparator = getCurrentSeparator(str);
            if(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator() != newSeparator){
                changeFormat(newSeparator);
            }

            BigDecimal bd = (BigDecimal)mDecimalFormat.parse(str);
            return bd.setScale(mMaximumFractionDigits, BigDecimal.ROUND_HALF_UP);
        }catch (ParseException ex){
            //setInputType(InputType.TYPE_TEXT_FLAG_NO_SUGGESTIONS);
            //setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
        }
        return null;
    }

    public void setValue(BigDecimal value) {
        String formattedValue = "";
        if(value != null){
            formattedValue = mDecimalFormat.format(value);
        }
        setText(formattedValue);
    }

    private char getCurrentSeparator(String str) {
        int dotsCount = StringUtils.countMatches(str, String.valueOf(Constants.DOT));
        int commasCount = StringUtils.countMatches(str, String.valueOf(Constants.COMMA));

        if(dotsCount > 0 && commasCount > 0){
            setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            return Constants.COMMA;
        }else if(dotsCount > 0){
            if(dotsCount > 1){
                setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            }
            return Constants.DOT;
        }else {
            if(commasCount > 1){
                setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            }
            return Constants.COMMA;
        }
    }

    private void changeFormat(char separator) {
        DecimalFormatSymbols otherSymbols = new DecimalFormatSymbols();
        otherSymbols.setDecimalSeparator(separator);

        mDecimalFormat = new DecimalFormat("0", otherSymbols);
        mDecimalFormat.setParseBigDecimal(true);
        mDecimalFormat.setMinimumFractionDigits(mMinimumFractionDigits);
        mDecimalFormat.setMaximumFractionDigits(mMaximumFractionDigits);
    }


}
