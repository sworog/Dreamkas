package project.lighthouse.autotests.jbehave;

import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.jbehave.ThucydidesJUnitStories;
import project.lighthouse.autotests.StaticDataCollections;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    private static final String CURRENT_BRANCH = "lighthouse.autotests.branch";
    private static final String IMPLICITLY_WAIT = "webdriver.timeouts.implicitlywait";
    private static final String WEB_DRIVER_BASE_URL = "webdriver.base.url";

    EnvironmentVariables environmentVariables = getEnvironmentVariables();

    public AcceptanceTestSuite() {
        setImplicitlyWaitTimeOut();
        setWebDriverBaseUrl();
        runTestSuite();
    }

    private void setImplicitlyWaitTimeOut() {
        String timeout = environmentVariables.getProperty(IMPLICITLY_WAIT, null);
        if (timeout != null) {
            StaticDataCollections.TIMEOUT = timeout;
        }
    }

    private void setWebDriverBaseUrl() {
        StaticDataCollections.WEB_DRIVER_BASE_URL = environmentVariables.getProperty(WEB_DRIVER_BASE_URL, null);
    }

    private void runTestSuite() {
        String branch = environmentVariables.getProperty(CURRENT_BRANCH, null);
        if (branch != null) {
            if (branch.startsWith("us-")) {
                findStoriesCalled(branch.substring(3) + "*");
            }
            if (branch.startsWith("sprint-")) {
                findStoriesIn("**/" + branch);
            }
        }
    }
}
