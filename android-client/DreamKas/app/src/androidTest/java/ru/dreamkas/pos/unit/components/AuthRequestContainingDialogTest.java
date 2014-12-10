package ru.dreamkas.pos.unit.components;

import android.os.Bundle;
import android.test.AndroidTestCase;
import android.text.method.DigitsKeyListener;

import java.math.BigDecimal;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.view.components.NumericEditText;
import ru.dreamkas.pos.view.popup.AuthRequestContainingDialog;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.core.IsNull.nullValue;

public class AuthRequestContainingDialogTest extends AndroidTestCase {
    private NumericEditText mEditText;

    @Override
    protected void setUp() throws Exception {
        super.setUp();
    }

    public void testTokenBundle(){
        String token = "test_token";

        AuthRequestContainingDialog dialog = new AuthRequestContainingDialog();
        Bundle bundle = new Bundle();
        bundle.putString(Constants.ACCESS_TOKEN, token);
        dialog.setArguments(bundle);

        assertThat("", dialog.getToken(), is(token));
    }
}
