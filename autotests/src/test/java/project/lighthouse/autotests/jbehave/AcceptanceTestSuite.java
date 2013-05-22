package project.lighthouse.autotests.jbehave;

import net.thucydides.core.steps.StepEventBus;
import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.jbehave.ThucydidesJUnitStories;
import project.lighthouse.autotests.StaticDataCollections;
import project.lighthouse.autotests.thucydides.TeamCityStepListener;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    public static final String CURRENT_BRANCH = "lighthouse.autotests.branch";
    private static final String IMPLICITY_WAIT = "webdriver.timeouts.implicitlywait";

    public AcceptanceTestSuite() {

        EnvironmentVariables environmentVariables = getEnvironmentVariables();
        String timeout = environmentVariables.getProperty(IMPLICITY_WAIT, null);
        if (timeout != null) {
            StaticDataCollections.TIMEOUT = timeout;
        }
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
