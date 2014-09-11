package ru.crystals.vaverjanov.dreamkas.unit.api;

import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;

import ru.crystals.vaverjanov.dreamkas.controller.AuthRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseRestClient_;
import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.Token;

public class AuthRequestTest extends InstrumentationTestCase {
    private AuthRequest authRequest;

    @Override
    protected void setUp() throws Exception {
        super.setUp();

        AuthObject ao = new AuthObject("webfront_webfront", "owner@lighthouse.pro", "lighthouse", "secret");

        authRequest = new AuthRequest();
        authRequest.setCredentials(ao);

        LighthouseRestClient_ restClient = new LighthouseRestClient_();
        authRequest.setRestClient(restClient);
    }

    @SmallTest
    public void test_loginLoadDataFromNetwork() throws Exception
    {
        Token response = authRequest.loadDataFromNetwork();
        assertTrue(response!=null);
    }
}
