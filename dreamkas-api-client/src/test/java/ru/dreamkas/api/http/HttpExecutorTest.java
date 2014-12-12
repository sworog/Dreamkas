package ru.dreamkas.api.http;

import org.apache.http.HttpEntity;
import org.apache.http.StatusLine;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.Mock;
import org.powermock.core.classloader.annotations.PrepareForTest;
import org.powermock.modules.junit4.PowerMockRunner;
import ru.dreamkas.api.AccessToken;
import ru.dreamkas.api.objects.abstraction.AbstractObject;
import ru.dreamkas.apiStorage.ApiStorage;

import java.io.IOException;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;
import static org.mockito.Mockito.any;
import static org.mockito.MockitoAnnotations.initMocks;
import static org.powermock.api.mockito.PowerMockito.mockStatic;
import static org.powermock.api.mockito.PowerMockito.when;

@RunWith(PowerMockRunner.class)
@PrepareForTest( { EntityUtils.class })
public class HttpExecutorTest {

    @Mock
    AccessToken accessToken;

    @Mock
    HttpClientFacade httpClientFacade;

    @Mock
    CloseableHttpClient closeableHttpClient;

    @Mock
    CloseableHttpResponse httpResponse;

    @Mock
    HttpEntity httpEntity;

    @Mock
    StatusLine statusLine;

    @Mock
    AbstractObject abstractObject;


    @Before
    public void before() throws IOException {
        initMocks(this);
        mockStatic(EntityUtils.class);
        when(EntityUtils.toString(httpEntity, "UTF-8")).thenReturn("{}");
        when(abstractObject.getApiUrl()).thenReturn("/url");
        when(abstractObject.getJsonObject()).thenReturn(new JSONObject());
        when(accessToken.get()).thenReturn("access_token");
        when(httpClientFacade.build()).thenReturn(closeableHttpClient);
        when(httpClientFacade.build().execute(any(HttpUriRequest.class))).thenReturn(httpResponse);
        when(httpResponse.getEntity()).thenReturn(httpEntity);
        when(httpResponse.getStatusLine()).thenReturn(statusLine);
        when(statusLine.getStatusCode()).thenReturn(200);
    }

    @Test
    public void testGetRequestExecution() throws Exception {
        HttpRequestable httpRequestable = HttpExecutor.getHttpRequestable("email", "password");
        initHttpExecutorWithParams((HttpExecutor)httpRequestable);
        assertThat(httpRequestable.executeGetRequest("test"), is("{}"));
    }

    private void initHttpExecutorWithParams(HttpExecutor httpExecutor) {
        httpExecutor.setHttpClientFacade(httpClientFacade);
        httpExecutor.setAccessToken(accessToken);
    }

    @Test
    public void testIfHttpEntityIsNullRequestReturnsEmptyJson() throws Exception {
        initBaseUrlProperty();
        HttpRequestable httpRequestable = HttpExecutor.getHttpRequestable("email", "password");
        when(httpResponse.getEntity()).thenReturn(null);
        initHttpExecutorWithParams((HttpExecutor)httpRequestable);
        httpRequestable.executePostRequest(abstractObject);
    }

    @Test
    public void testPostRequestExecution() throws IOException, JSONException {
        initBaseUrlProperty();
        HttpRequestable httpRequestable = HttpExecutor.getHttpRequestable("email", "password");
        initHttpExecutorWithParams((HttpExecutor) httpRequestable);
        httpRequestable.executePostRequest(abstractObject);
    }

    @Test
    public void testPutRequestExecution() throws IOException, JSONException {
        initBaseUrlProperty();
        HttpRequestable httpRequestable = HttpExecutor.getHttpRequestable("email", "password");
        initHttpExecutorWithParams((HttpExecutor) httpRequestable);
        httpRequestable.executePutRequest("http://url", abstractObject.getJsonObject());
    }

    @Test
    public void testLinkRequestExecution() throws IOException, JSONException {
        initBaseUrlProperty();
        HttpRequestable httpRequestable = HttpExecutor.getHttpRequestable("email", "password");
        initHttpExecutorWithParams((HttpExecutor) httpRequestable);
        httpRequestable.executeLinkRequest("http://url", "header");
    }

    @Test
    public void testSimplePostExecution() throws IOException {
        HttpExecutor httpExecutor = (HttpExecutor) HttpExecutor.getSimpleHttpRequestable();
        httpExecutor.setHttpClientFacade(httpClientFacade);
        assertThat(httpExecutor.executeSimplePostRequest("http://localhost", "url"), is("{}"));
    }

    private void initBaseUrlProperty() {
        ApiStorage.getConfigurationVariableStorage().setProperty("webdriver.base.url", "http://test.autotests.webfront.lighthouse.pro");
    }
}
