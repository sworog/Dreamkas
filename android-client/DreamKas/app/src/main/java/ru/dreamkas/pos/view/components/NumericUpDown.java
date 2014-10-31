package ru.dreamkas.pos.view.components;

import android.content.Context;
import android.graphics.drawable.Drawable;
import android.text.InputType;
import android.util.AttributeSet;
import android.util.Pair;
import android.widget.EditText;
import android.widget.LinearLayout;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.TextChange;
import org.androidannotations.annotations.ViewById;

import java.math.BigDecimal;

import ru.dreamkas.pos.ConverterHelper;
import ru.dreamkas.pos.R;

@EViewGroup(R.layout.numeric_updown)
public class NumericUpDown extends LinearLayout {
    //private float mValue = -1;

    @ViewById
    EditText txtValue;

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
        txtValue.setText(String.valueOf(0));
        txtValue.setSelection(txtValue.getText().length());
        //txtValue.setError("Неверный формат");
    }

    @Click(R.id.btnDown)
    void decrement(){
        changeValue(-1);
    }

    @Click(R.id.btnUp)
    void increment(){
        changeValue(1);
    }

    private void changeValue(int delta) {
        //int cursorPosition = myEditText.getSelectionStart();

        BigDecimal value = getValue();
        if(value != null){
            BigDecimal result = getValue().add(new BigDecimal(delta));

            if(result.signum() < 0){
                result = BigDecimal.ZERO;
            }

            txtValue.setText(result.toString());
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
            txtValue.setError("Неверный формат");
        }
        return null;
    }

    @TextChange(R.id.txtValue)
    void onTextChangesOnTxtValue() {
       /* if(txtValue.getText().length() == 0){
            init();
        }*/
    }
}
