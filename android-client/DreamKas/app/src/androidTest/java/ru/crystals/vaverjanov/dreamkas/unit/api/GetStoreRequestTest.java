package ru.crystals.vaverjanov.dreamkas.unit.api;

import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;
import ru.crystals.vaverjanov.dreamkas.controller.requests.AuthRequest_;
import ru.crystals.vaverjanov.dreamkas.controller.requests.GetStoresRequest;
import ru.crystals.vaverjanov.dreamkas.controller.requests.GetStoresRequest_;
import ru.crystals.vaverjanov.dreamkas.model.api.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;

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
    }

    @SmallTest
    public void test_getStoresLoadDataFromNetwork() throws Exception
    {
        NamedObjects response = storesRequest.loadDataFromNetwork();
        assertTrue(response!=null);
    }
}
