package ru.dreamkas.api.factories;

import org.json.JSONException;
import org.junit.Before;
import org.junit.Test;
import org.mockito.Mock;
import org.mockito.MockitoAnnotations;
import ru.dreamkas.api.http.HttpRequestable;
import ru.dreamkas.api.objects.abstraction.AbstractObject;

import java.io.IOException;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ApiFactoryTest {

    @Mock
    HttpRequestable httpRequestable;

    @Mock
    AbstractObject object;

    @Before
    public void before() throws IOException, JSONException {
        MockitoAnnotations.initMocks(this);
    }

    @Test
    public void testCreateObjectMethod() throws IOException, JSONException {
        ApiFactory apiFactory = new ApiFactory("email", "password");
        apiFactory.setHttpRequestable(httpRequestable);
        httpRequestable.executePostRequest(object);
        assertThat(
                apiFactory.createObject(object),
                is(object));
    }
}
