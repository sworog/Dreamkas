package ru.dreamkas.api.http;

import org.hamcrest.Matchers;
import org.junit.Test;

import static org.junit.Assert.assertThat;

public class HttpClientFacadeTest {

    @Test
    public void testCloseableHttpClientIsNotNull() {
        assertThat(new HttpClientFacade().build(), Matchers.notNullValue());
    }
}
