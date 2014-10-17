package ru.dreamkas.pos.unit.api;

import android.test.AndroidTestCase;
import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;
import ru.dreamkas.pos.controller.requests.AuthRequest_;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.Token;

public class AuthRequestTest extends AndroidTestCase//InstrumentationTestCase
{
    private AuthRequest_ authRequest;

    @Override
    protected void setUp() throws Exception
    {
        super.setUp();

        AuthObject ao = new AuthObject("webfront_webfront", "owner@lighthouse.pro", "lighthouse", "secret");

        authRequest = AuthRequest_.getInstance_(getContext());
        authRequest.setCredentials(ao);
    }

    @SmallTest
    public void test_loginLoadDataFromNetwork() throws Exception
    {
        try {
            Thread.sleep(5000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        Token response = authRequest.loadDataFromNetwork();
        assertTrue(response!=null);
    }
}
