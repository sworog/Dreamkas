package project.lighthouse.autotests.jbehave;

import net.thucydides.core.ThucydidesSystemProperty;
import net.thucydides.jbehave.ThucydidesJUnitStories;
import project.lighthouse.autotests.StaticData;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    private static final String BRANCH = "lighthouse.autotests.branch";
    private static final String THREADS = "lighthouse.threads";
    private static final String DEMO_MODE = "lighthouse.demo";

    public AcceptanceTestSuite() {
        setImplicitlyWaitTimeOut();
        setWebDriverBaseUrl();
        setThreads();
        findStoriesByBranch();
        setDemoMode();
    }

    private void setImplicitlyWaitTimeOut() {
        StaticData.TIMEOUT = getEnvironmentVariables()
                .getPropertyAsInteger(
                        ThucydidesSystemProperty.TIMEOUTS_IMPLICIT_WAIT.getPropertyName(),
                        StaticData.TIMEOUT
                );
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
