package ru.dreamkas.pos.unit.components;

import android.test.AndroidTestCase;
import android.text.method.DigitsKeyListener;

import java.math.BigDecimal;
import ru.dreamkas.pos.view.components.NumericEditText;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.core.IsNull.nullValue;

public class NumericEditTextTest extends AndroidTestCase {
    private NumericEditText mEditText;

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        mEditText = new NumericEditText(getContext());
        mEditText.setKeyListener(DigitsKeyListener.getInstance("0123456789.,"));
    }

    public void testSetMin2Max3FractionDigitsZeroValue(){
        mEditText.setMinMaxFractionDigits(2, 3);
        mEditText.setValue(BigDecimal.ZERO);
        assertThat("", mEditText.getText().toString(), is("0,00"));
    }

    public void testSetMin3Max3FractionDigitsZeroValue(){
        mEditText.setMinMaxFractionDigits(3, 3);
        mEditText.setValue(BigDecimal.ZERO);
        assertThat("", mEditText.getText().toString(), is("0,000"));
    }

    public void testSetMin3Max3FractionDigitsTestValue(){
        mEditText.setMinMaxFractionDigits(3, 3);
        mEditText.setValue(new BigDecimal(2.45));
        assertThat("", mEditText.getText().toString(), is("2,450"));
    }

    public void testSetMin3Max3FractionDigitsValue10(){
        mEditText.setMinMaxFractionDigits(0, 1);
        mEditText.setValue(BigDecimal.TEN);
        assertThat("", mEditText.getText().toString(), is("10"));
    }

    public void testSetMin3Max3FractionDigitsValue10_100(){
        mEditText.setMinMaxFractionDigits(0, 4);
        mEditText.setValue(new BigDecimal(10.100));
        assertThat("", mEditText.getText().toString(), is("10,1"));
    }

    public void testSetMin3Max3FractionDigitsWrongValue(){
        mEditText.setMinMaxFractionDigits(3, 3);
        mEditText.setText("56,,870");
        assertThat("", mEditText.getValue(), is(nullValue()));
        assertThat("", mEditText.getError(), not(nullValue()));
    }

    public void testFilterWithWrongSymbols() {
        mEditText.setMinMaxFractionDigits(2, 3);
        String origin = "56,555";
        mEditText.setText(origin);
        mEditText.getText().insert(0, "asdvb");
        assertThat("", mEditText.getText().toString(), is(origin));
    }

    public void testFilterWithRightValue() {
        mEditText.setMinMaxFractionDigits(2, 3);
        String origin = "56,555";
        mEditText.setText(origin);
        mEditText.getText().insert(0, "1");
        assertThat("", mEditText.getText().toString(), is("156,555"));
    }

    public void testFilterWithTooManyDigitsAfterSeparator() {
        mEditText.setMinMaxFractionDigits(2, 3);
        String origin = "56,555";
        mEditText.setText(origin);
        mEditText.getText().insert(4, "1");
        assertThat("", mEditText.getText().toString(), is("56,555"));
    }

    public void testTwoSeparators() {
        mEditText.setMinMaxFractionDigits(1, 3);

        mEditText.setValue(BigDecimal.ONE);

        assertThat("", mEditText.getText().toString(), is("1,0"));

        mEditText.setText("1.1");

        BigDecimal result = new BigDecimal(1.1);
        result = result.setScale(3,BigDecimal.ROUND_HALF_UP);

        assertThat("", mEditText.getValue(), is(result));
    }
}
