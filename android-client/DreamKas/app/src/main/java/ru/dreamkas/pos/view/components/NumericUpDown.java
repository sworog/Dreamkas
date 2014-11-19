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
    NumericEditText txtValue;
    private ValueChangedListener mValueChangedListener;


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
        setValue(BigDecimal.ZERO);
        txtValue.setSelection(txtValue.getText().length());
    }

    @Click(R.id.btnDown)
    void decrement(){
        changeValue(-1);
    }

    @Click(R.id.btnUp)
    void increment(){
        changeValue(1);
    }

    @TextChange(R.id.txtValue)
    void onTextChangesOnTxtValue() {
        BigDecimal value = BigDecimal.ZERO;

        if (txtValue.length() > 0){
            txtValue.setError(null);

            try{
                value = txtValue.getValue();
                if(value.signum() < 0){
                    txtValue.setError(getContext().getResources().getString(R.string.msg_error_negative_value));
                }else if(value.signum() == 0) {
                    txtValue.setError(getContext().getResources().getString(R.string.msg_error_zero_value));
                }
            }catch (Exception ex){
                txtValue.setError(getContext().getResources().getString(R.string.msg_error_wrong_format));
            }
        }else if(txtValue.length() == 0){
            txtValue.setError(getContext().getResources().getString(R.string.msg_error_empty_value));
        }

        if(mValueChangedListener != null){
            mValueChangedListener.changedTo(value);
        }
    }

    private void changeValue(int delta) {
        BigDecimal value = txtValue.getValue();
        if(value != null){
            BigDecimal result = txtValue.getValue().add(new BigDecimal(delta));

            if(result.signum() < 0){
                result = BigDecimal.ZERO;
            }

            setValue(result);
            txtValue.setSelection(txtValue.getText().length());
        }
    }

    public CharSequence getError(){
        return txtValue.getError();
    }

    public void setValueChangedListener(ValueChangedListener listener) {
        mValueChangedListener = listener;
    }

    public void setValue(BigDecimal value) {
        txtValue.setValue(value);
        if(mValueChangedListener != null){
            mValueChangedListener.changedTo(value);
        }
    }

    public BigDecimal getValue(){
        return txtValue.getValue();
    }
}
