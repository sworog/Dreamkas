package project.lighthouse.autotests.steps.api.departmentManager;

import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.ApiConnect;

public class DepartmentManagerApi extends ScenarioSteps {

    protected ApiConnect apiConnect;

    public static final String DEFAULT_USER_NAME = "departmentManager";

    public DepartmentManagerApi() throws JSONException {
        apiConnect = new ApiConnect("departmentManagerApi", "lighthouse");
    }
}
