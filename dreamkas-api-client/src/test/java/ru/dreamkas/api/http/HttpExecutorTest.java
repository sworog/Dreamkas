package ru.dreamkas.api.http;

import org.apache.http.HttpEntity;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.util.EntityUtils;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.Mock;
import org.mockito.Mockito;
import org.mockito.MockitoAnnotations;
import org.powermock.api.mockito.PowerMockito;
import org.powermock.core.classloader.annotations.PrepareForTest;
import org.powermock.modules.junit4.PowerMockRunner;
import ru.dreamkas.api.AccessToken;

import java.io.IOException;

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
    HttpUriRequest httpUriRequest;

    @Mock
    CloseableHttpResponse httpResponse;

    @Mock
    HttpEntity httpEntity;

    @Before
    public void before() {
        MockitoAnnotations.initMocks(this);
    }

    @Test
    public void testGetRequestExecution() throws IOException {
        HttpExecutor httpExecutor = (HttpExecutor)HttpExecutor.getSimpleHttpRequestable();
        httpExecutor.setHttpClientFacade(httpClientFacade);
        httpExecutor.setAccessToken(accessToken);
        Mockito.when(accessToken.get()).thenReturn("access_token");
        Mockito.when(httpClientFacade.build()).thenReturn(closeableHttpClient);
        Mockito.when(httpClientFacade.build().execute(httpUriRequest)).thenReturn(httpResponse);
        Mockito.when(httpResponse.getEntity()).thenReturn(httpEntity);

        PowerMockito.mockStatic(EntityUtils.class);

//        PowerMo.(EntityUtils.toString()).andReturn(expectedId);

        httpExecutor.executeGetRequest("test");
    }
}
