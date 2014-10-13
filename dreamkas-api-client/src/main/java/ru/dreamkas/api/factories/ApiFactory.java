package ru.dreamkas.api.factories;

import org.json.JSONException;
import ru.dreamkas.api.http.HttpExecutor;
import ru.dreamkas.api.http.HttpRequestable;
import ru.dreamkas.api.objects.abstraction.AbstractObject;

import java.io.IOException;

public class ApiFactory {

    private HttpRequestable httpRequestable;

    public ApiFactory(String email, String password) {
        httpRequestable = HttpExecutor.getHttpRequestable(email, password);
    }

    public AbstractObject createObject(AbstractObject object) throws IOException, JSONException {
        httpRequestable.executePostRequest(object);
        return object;
    }
}
