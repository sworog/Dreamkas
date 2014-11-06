package ru.dreamkas.pos.view.components;

import android.content.Context;
import android.text.InputType;
import android.util.AttributeSet;
import android.widget.EditText;
import android.widget.LinearLayout;
import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.TextChange;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.ObjectUtils;
import java.math.BigDecimal;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.listeners.ValueChangedListener;

@EViewGroup(R.layout.numeric_updown)
public class NumericUpDown extends LinearLayout {
    @ViewById
    EditText txtValue;
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
                value = new BigDecimal(txtValue.getText().toString());
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
            BigDecimal bd = new BigDecimal(txtValue.getText().toString());
            bd.setScale(3, BigDecimal.ROUND_HALF_EVEN);
            return bd;

        }catch (NumberFormatException ex){
            txtValue.setInputType(InputType.TYPE_TEXT_FLAG_NO_SUGGESTIONS);
            txtValue.setError(DreamkasApp.getResourceString(R.string.msg_error_wrong_format));
        }
        return null;
    }

    public CharSequence getError(){
        return txtValue.getError();
    }

    public void setValueChangedListener(ValueChangedListener listener) {
        mValueChangedListener = listener;
    }

    public void setValue(BigDecimal value) {
        txtValue.setText(ObjectUtils.getDisplayString(value));
        if(mValueChangedListener != null){
            mValueChangedListener.changedTo(value);
        }
    }
}
