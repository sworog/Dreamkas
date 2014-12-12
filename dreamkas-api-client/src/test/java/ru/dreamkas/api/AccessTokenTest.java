package ru.dreamkas.api;

import org.json.JSONException;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.Mock;
import org.mockito.Mockito;
import org.powermock.core.classloader.annotations.PrepareForTest;
import org.powermock.modules.junit4.PowerMockRunner;
import ru.dreamkas.api.http.AnonymousHttpRequestable;
import ru.dreamkas.api.http.HttpExecutor;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.variable.UserVariableStorage;

import java.io.IOException;
import java.util.HashMap;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;
import static org.powermock.api.mockito.PowerMockito.mockStatic;
import static org.powermock.api.mockito.PowerMockito.when;

@RunWith(PowerMockRunner.class)
@PrepareForTest( { HttpExecutor.class, ApiStorage.class})
public class AccessTokenTest {

    @Mock
    AnonymousHttpRequestable anonymousHttpRequestable;

    @Mock
    UserVariableStorage userVariableStorage;

    @Before
    public void before() throws IOException {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", "http://test.autotests.webfront.lighthouse.pro");
        mockStatic(HttpExecutor.class);
        when(HttpExecutor.getSimpleHttpRequestable()).thenReturn(anonymousHttpRequestable);
        when(anonymousHttpRequestable.executeSimplePostRequest(Mockito.anyString(), Mockito.anyString())).thenReturn("{access_token:token}");
    }

    @Test
    public void testGetAccessTokenIfTokenStorageIsEmpty() {
        assertThat(new AccessToken("email", "password").get(), is("token"));
    }

    @Test
    public void testGetAccessTokenIfTokenStorageContainRequiredToken() {
        mockStatic(ApiStorage.class);
        when(ApiStorage.getUserVariableStorage()).thenReturn(userVariableStorage);
        when(userVariableStorage.getUserTokens()).thenReturn(new HashMap<String, String>() {{put("email", "token");}});
        assertThat(new AccessToken("email", "password").get(), is("token"));
    }

    @Test(expected = AssertionError.class)
    public void testGetAccessTokenIfOauthObjectContainsBadJsonToken() throws IOException {
        when(anonymousHttpRequestable.executeSimplePostRequest(Mockito.anyString(), Mockito.anyString())).thenReturn("{wrong}");
        assertThat(new AccessToken("email", "password").get(), is("token"));
    }

    @After
    public void after() {
        ApiStorage.getUserVariableStorage().getUserTokens().clear();
    }
}
