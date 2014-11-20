package ru.dreamkas.pos.unit.components;

import android.app.Activity;
import android.test.AndroidTestCase;
import android.text.method.DigitsKeyListener;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.math.BigDecimal;

import ru.dreamkas.pos.view.components.NumericEditText;
import ru.dreamkas.pos.view.components.NumericUpDown_;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.core.IsNull.nullValue;

public class NumericUpDownTest extends AndroidTestCase {
    private NumericUpDown_ mComponent;

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        mComponent = new NumericUpDown_(getContext());
    }

   /* public void testIncrementValue() throws NoSuchMethodException, InvocationTargetException, IllegalAccessException {
        mComponent.onViewChanged(mComponent);
        mComponent.setValue(BigDecimal.ONE);

        Method m = NumericUpDown_.class.getMethod("increment", null);
        m.invoke(mComponent, null);

        assertThat("", mComponent.getValue(), is(new BigDecimal(2)));
    }*/
}
