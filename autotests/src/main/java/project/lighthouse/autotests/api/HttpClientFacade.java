package project.lighthouse.autotests.api;

import org.apache.http.client.config.RequestConfig;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClientBuilder;

public class HttpClientFacade {

    private static final Integer CONNECTION_TIMEOUT = Integer.getInteger("api.timeout", 60000);

    public CloseableHttpClient build() {
        RequestConfig requestConfig = RequestConfig
                .custom()
                .setConnectTimeout(CONNECTION_TIMEOUT)
                .setConnectionRequestTimeout(CONNECTION_TIMEOUT)
                .setSocketTimeout(CONNECTION_TIMEOUT)
                .build();
        return HttpClientBuilder
                .create()
                .setDefaultRequestConfig(requestConfig)
                .build();
    }
}
