package ru.dreamkas.pos.view.components;

import android.content.Context;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Spanned;
import android.text.TextUtils;
import android.util.AttributeSet;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.common.base.Joiner;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.BeforeTextChange;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.TextChange;
import org.androidannotations.annotations.ViewById;
import org.apache.commons.lang3.StringUtils;
import org.springframework.util.ObjectUtils;
import java.math.BigDecimal;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.NumberFormat;
import java.text.ParseException;
import java.util.Arrays;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.listeners.ValueChangedListener;

@EViewGroup(R.layout.numeric_updown)
public class NumericUpDown extends LinearLayout {
    @ViewById
    EditText txtValue;
    private ValueChangedListener mValueChangedListener;
    private DecimalFormat mDecimalFormat;

    public NumericUpDown(Context context) {
        super(context);
    }

    public NumericUpDown(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public NumericUpDown(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
    }

    @AfterViews
    void init(){
        changeFormat(Constants.COMMA);

        setValue(BigDecimal.ZERO);
        txtValue.setSelection(txtValue.getText().length());

        InputFilter filter = new InputFilter() {
            public CharSequence filter(CharSequence source, int start, int end, Spanned dest, int dstart, int dend) {
                String currentValue = txtValue.getText().toString();
                String separator = String.valueOf(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator());

                String dot = String.valueOf(Constants.DOT);
                String comma = String.valueOf(Constants.COMMA);

                if((source.equals(dot) || source.equals(comma)) && (currentValue.contains(separator) || currentValue.contains(separator))){
                    return "";
                }

                String[] valueParts = currentValue.split(separator);

                if(valueParts.length > 1){
                    String lastBlock = valueParts[valueParts.length - 1];
                    if(lastBlock.length()==Constants.SCALE_QUANTITY){
                        return "";
                    }
                }

                return null;
            }
        };

        txtValue.setFilters(new InputFilter[] { filter });
    }

    @Click(R.id.btnDown)
    void decrement(){
        changeValue(-1);
    }

    @Click(R.id.btnUp)
    void increment(){
        changeValue(1);
    }

   /* @BeforeTextChange(R.id.txtValue)
    void onBeforeTextChangesOnTxtValue(TextView tv, CharSequence text){
        String separator = String.valueOf(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator());
        String[] valueParts = text.toString().split(separator);

        if(valueParts.length > 1){
            String lastBlock = valueParts[valueParts.length - 1];
            if(lastBlock.length()>Constants.SCALE_QUANTITY){
                int delta = lastBlock.length() - Constants.SCALE_QUANTITY;
                valueParts[valueParts.length - 1] = lastBlock.substring(0, lastBlock.length()-delta);
                tv.setText(StringUtils.join(valueParts, separator));
            }
        }
    }*/

    @TextChange(R.id.txtValue)
    void onTextChangesOnTxtValue() {
        BigDecimal value = BigDecimal.ZERO;

        if (txtValue.length() > 0){
            txtValue.setError(null);

            try{
                value = getValue();
                if(value.signum() < 0){
                    txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_negative_value));
                }else if(value.signum() == 0) {
                    txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_zero_value));
                }
            }catch (Exception ex){
                txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            }
        }else if(txtValue.length() == 0){
            txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_empty_value));
        }

        if(mValueChangedListener != null){
            mValueChangedListener.changedTo(value);
        }
    }

    private void changeValue(int delta) {
        BigDecimal value = getValue();
        if(value != null){
            BigDecimal result = getValue().add(new BigDecimal(delta));

            if(result.signum() < 0){
                result = BigDecimal.ZERO;
            }

            setValue(result);
            txtValue.setSelection(txtValue.getText().length());
        }
    }

    public BigDecimal getValue(){
        try
        {
            String str = txtValue.getText().toString();

            char newSeparator = getCurrentSeparator(str);
            if(mDecimalFormat.getDecimalFormatSymbols().getDecimalSeparator() != newSeparator){
                changeFormat(newSeparator);
            }

            BigDecimal bd = (BigDecimal)mDecimalFormat.parse(str);
            return bd.setScale(Constants.SCALE_QUANTITY, BigDecimal.ROUND_HALF_UP);
        }catch (ParseException ex){
            txtValue.setInputType(InputType.TYPE_TEXT_FLAG_NO_SUGGESTIONS);
            txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
        }
        return null;
    }

    private void changeFormat(char separator) {
        DecimalFormatSymbols otherSymbols = new DecimalFormatSymbols();
        otherSymbols.setDecimalSeparator(separator);

        mDecimalFormat = new DecimalFormat("", otherSymbols);
        mDecimalFormat.setParseBigDecimal(true);
        mDecimalFormat.setMinimumFractionDigits(1);
        mDecimalFormat.setMaximumFractionDigits(Constants.SCALE_QUANTITY);
    }

    private char getCurrentSeparator(String str) {

        int dotsCount = StringUtils.countMatches(str, String.valueOf(Constants.DOT));
        int commasCount = StringUtils.countMatches(str, String.valueOf(Constants.COMMA));

        if(dotsCount > 0 && commasCount > 0){
            txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            return Constants.COMMA;
        }else if(dotsCount > 0){
            if(dotsCount > 1){
                txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            }
            return Constants.DOT;
        }else {
            if(commasCount > 1){
                txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
            }
            return Constants.COMMA;
        }
    }

    public CharSequence getError(){
        return txtValue.getError();
    }

    public void setValueChangedListener(ValueChangedListener listener) {
        mValueChangedListener = listener;
    }

    public void setValue(BigDecimal value) {
        txtValue.setText(mDecimalFormat.format(value));
        //txtValue.setText(ObjectUtils.getDisplayString(value));
        if(mValueChangedListener != null){
            mValueChangedListener.changedTo(value);
        }
    }
}
