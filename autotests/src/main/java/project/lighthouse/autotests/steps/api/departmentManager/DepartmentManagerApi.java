package project.lighthouse.autotests.steps.api.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;

import java.io.IOException;

public class DepartmentManagerApi extends ScenarioSteps {

    protected ApiConnect apiConnect;

    public static final String DEFAULT_USER_NAME = "departmentManager";

    public DepartmentManagerApi() {
        try {
            apiConnect = new ApiConnect("departmentManagerApi", "lighthouse");
        } catch (JSONException | IOException e) {
            Assert.fail(e.getMessage());
        }
    }
}
