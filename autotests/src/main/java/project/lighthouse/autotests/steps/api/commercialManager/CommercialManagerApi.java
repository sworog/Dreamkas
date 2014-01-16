package project.lighthouse.autotests.steps.api.commercialManager;

import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;

import java.io.IOException;

public class CommercialManagerApi extends ScenarioSteps {

    protected ApiConnect apiConnect;

    public CommercialManagerApi() throws IOException, JSONException {
        apiConnect = new ApiConnect("commercialManagerApi", "lighthouse");
    }
}
