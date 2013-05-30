package project.lighthouse.autotests.jbehave;

import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.jbehave.ThucydidesJUnitStories;
import project.lighthouse.autotests.StaticDataCollections;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    private static final String CURRENT_BRANCH = "lighthouse.autotests.branch";
    private static final String IMPLICITY_WAIT = "webdriver.timeouts.implicitlywait";
    private static final String WEB_DRIVER_BASE_URL = "webdriver.base.url";

    public AcceptanceTestSuite() {

        EnvironmentVariables environmentVariables = getEnvironmentVariables();
        String timeout = environmentVariables.getProperty(IMPLICITY_WAIT, null);
        if (timeout != null) {
            StaticDataCollections.TIMEOUT = timeout;
        }
        StaticDataCollections.WEB_DRIVER_BASE_URL = environmentVariables.getProperty(WEB_DRIVER_BASE_URL, null);
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
