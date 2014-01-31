package project.lighthouse.autotests.jbehave;

import net.thucydides.jbehave.ThucydidesJUnitStories;
import project.lighthouse.autotests.StaticData;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    private static final String BRANCH = "lighthouse.autotests.branch";
    private static final String THREADS = "lighthouse.threads";
    private static final String DEMO_MODE = "lighthouse.demo";
    private static final String TIMEOUT = "lighthouse.timeouts.implicitlywait";
    private static final String PRE_LOADER_TIMEOUT = "lighthouse.timeouts.preloaderwait";
    private static final String VALIDATION_ERROR_TIMEOUT = "lighthouse.timeouts.validationerrorwait";

    public AcceptanceTestSuite() {
        setWaitTimeOuts();
        setWebDriverBaseUrl();
        setThreads();
        findStoriesByBranch();
        setDemoMode();
    }

    private void setWaitTimeOuts() {
        StaticData.DEFAULT_TIMEOUT = getEnvironmentVariables()
                .getPropertyAsInteger(TIMEOUT, StaticData.DEFAULT_TIMEOUT);
        StaticData.DEFAULT_PRE_LOADER_TIMEOUT = getEnvironmentVariables()
                .getPropertyAsInteger(PRE_LOADER_TIMEOUT, StaticData.DEFAULT_PRE_LOADER_TIMEOUT);
        StaticData.DEFAULT_VALIDATION_ERROR_TIMEOUT = getEnvironmentVariables()
                .getPropertyAsInteger(VALIDATION_ERROR_TIMEOUT, StaticData.DEFAULT_VALIDATION_ERROR_TIMEOUT);
    }

    private void setWebDriverBaseUrl() {
        StaticData.WEB_DRIVER_BASE_URL = getSystemConfiguration().getBaseUrl();
    }

    private void findStoriesByBranch() {
        String branch = getEnvironmentVariables().getProperty(BRANCH, null);
        if (branch != null) {
            if (branch.startsWith("us-")) {
                findStoriesCalled(branch.substring(3) + "*");
            }
            if (branch.startsWith("sprint-")) {
                findStoriesIn("**/" + branch);
            }
        }
    }

    private void setThreads() {
        Integer threads = getEnvironmentVariables().getPropertyAsInteger(THREADS, 1);
        configuredEmbedder().embedderControls().useThreads(threads);
    }

    private void setDemoMode() {
        StaticData.demoMode = getEnvironmentVariables()
                .getPropertyAsBoolean(DEMO_MODE, false);
    }
}
