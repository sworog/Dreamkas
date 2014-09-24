package ru.crystals.vaverjanov.dreamkas.unit.api;

import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;

import com.octo.android.robospice.persistence.DurationInMillis;

import ru.crystals.vaverjanov.dreamkas.controller.AuthRequest;
import ru.crystals.vaverjanov.dreamkas.controller.AuthRequest_;
import ru.crystals.vaverjanov.dreamkas.controller.GetStoresRequest;
import ru.crystals.vaverjanov.dreamkas.controller.GetStoresRequest_;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseRestClient_;
import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.Token;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;

public class GetStoreRequestTest extends InstrumentationTestCase {
    private GetStoresRequest storesRequest;


    @Override
    protected void setUp() throws Exception
    {
        super.setUp();

        AuthObject ao = new AuthObject("webfront_webfront", "owner@lighthouse.pro", "lighthouse", "secret");
        AuthRequest_ authRequest = AuthRequest_.getInstance_(getInstrumentation().getContext());
        authRequest.setCredentials(ao);
        Token response = authRequest.loadDataFromNetwork();

        storesRequest = GetStoresRequest_.getInstance_(getInstrumentation().getContext());
        storesRequest.setToken(response.getAccess_token());
       // throw new Exception();
    }

    @SmallTest
    public void test_getStoresLoadDataFromNetwork() throws Exception
    {
        NamedObjects response = storesRequest.loadDataFromNetwork();
        assertTrue(response!=null);
    }
}
