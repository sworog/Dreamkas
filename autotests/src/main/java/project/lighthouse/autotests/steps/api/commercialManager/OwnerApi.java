package project.lighthouse.autotests.steps.api.commercialManager;

import junit.framework.Assert;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;

import java.io.IOException;

public class OwnerApi extends ScenarioSteps {

    protected ApiConnect apiConnect;

    public OwnerApi() {
        try {
            apiConnect = new ApiConnect("owner@lighthouse.pro", "lighthouse");
        } catch (IOException | JSONException e) {
            Assert.fail(e.getMessage());
        }
    }
}
