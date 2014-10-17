package ru.dreamkas.pos.unit.api;

import android.test.AndroidTestCase;
import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;

import ru.dreamkas.pos.controller.DreamkasRestClient_;
import ru.dreamkas.pos.controller.requests.AuthRequest_;
import ru.dreamkas.pos.controller.requests.GetStoresRequest;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.collections.NamedObjects;
import ru.dreamkas.pos.model.api.Token;

public class GetStoreRequestTest extends AndroidTestCase {//InstrumentationTestCase {

    private DreamkasRestClient_ mRestClient;

    @Override
    protected void setUp() throws Exception
    {
        super.setUp();

        AuthObject ao = new AuthObject("webfront_webfront", "owner@lighthouse.pro", "lighthouse", "secret");
        AuthRequest_ authRequest = AuthRequest_.getInstance_(getContext());
        authRequest.setCredentials(ao);
        Token authResponse = authRequest.loadDataFromNetwork();

        mRestClient = new DreamkasRestClient_();
        mRestClient.setHeader("Authorization", "Bearer " + authResponse.getAccess_token());
    }

    @SmallTest
    public void test_getStoresLoadDataFromNetwork() throws Exception
    {
        GetStoresRequest storesRequest = new GetStoresRequest();

        storesRequest.init(mRestClient);
        NamedObjects response = storesRequest.loadDataFromNetwork();
        assertTrue(response!=null);
    }
}
