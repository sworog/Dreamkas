package project.lighthouse.autotests.api;

import org.json.JSONException;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

import java.io.IOException;

/**
 * Interface to implement http post request
 */
public interface HttpRequestable {

    public void executePostRequest(AbstractObject object) throws IOException, JSONException;
}
