package ru.dreamkas.api.http;

import org.apache.http.HttpEntity;
import org.apache.http.StatusLine;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.util.EntityUtils;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.Mock;
import org.powermock.core.classloader.annotations.PrepareForTest;
import org.powermock.modules.junit4.PowerMockRunner;
import ru.dreamkas.api.AccessToken;

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


    @Before
    public void before() {
        initMocks(this);
    }

    @Test
    public void testGetRequestExecution() throws Exception {
        HttpExecutor httpExecutor = (HttpExecutor)HttpExecutor.getSimpleHttpRequestable();
        httpExecutor.setHttpClientFacade(httpClientFacade);
        httpExecutor.setAccessToken(accessToken);
        when(accessToken.get()).thenReturn("access_token");
        when(httpClientFacade.build()).thenReturn(closeableHttpClient);
        when(httpClientFacade.build().execute(any(HttpUriRequest.class))).thenReturn(httpResponse);
        when(httpResponse.getEntity()).thenReturn(httpEntity);
        when(httpResponse.getStatusLine()).thenReturn(statusLine);
        when(statusLine.getStatusCode()).thenReturn(200);
        mockStatic(EntityUtils.class);
        when(EntityUtils.toString(httpEntity, "UTF-8")).thenReturn("test");

        httpExecutor.executeGetRequest("test");
    }
}
