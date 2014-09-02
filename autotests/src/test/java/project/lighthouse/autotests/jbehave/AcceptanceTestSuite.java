package project.lighthouse.autotests.jbehave;

import ch.lambdaj.Lambda;
import net.thucydides.core.webdriver.WebdriverProxyFactory;
import net.thucydides.jbehave.ThucydidesJUnitStories;
import project.lighthouse.autotests.storage.Configurable;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.thucydides.RemoteWebDriverEventListener;

import java.util.List;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    private static final String STORIES = "lighthouse.autotests.stories";
    private static final String BRANCH = "lighthouse.autotests.branch";
    private static final String THREADS = "lighthouse.threads";
    private static final String DEMO_MODE = "lighthouse.demo";
    private static final String TIMEOUT = "lighthouse.timeouts.implicitlywait";
    private static final String PRE_LOADER_TIMEOUT = "lighthouse.timeouts.preloaderwait";
    private static final String VALIDATION_ERROR_TIMEOUT = "lighthouse.timeouts.validationerrorwait";

    private final Integer defaultTimeOut = getEnvironmentVariables().getPropertyAsInteger(TIMEOUT, 15);
    private final Integer defaultPreLoaderTimeOut = getEnvironmentVariables().getPropertyAsInteger(PRE_LOADER_TIMEOUT, 5);
    private final Integer defaultValidationErrorTimeOut = getEnvironmentVariables().getPropertyAsInteger(VALIDATION_ERROR_TIMEOUT, 10);

    private Configurable configurationStorage = Storage.getConfigurationVariableStorage();

    public AcceptanceTestSuite() {
        setWaitTimeOuts();
        setWebDriverBaseUrl();
        setThreads();
        findStoriesByBranch();
        findStoriesByStories();
        setDemoMode();
        parallelExecutionStart();
        registerWebDriverListener();
    }

    private void setWaitTimeOuts() {
        configurationStorage.setTimeOutProperty("default.timeout", defaultTimeOut);
        configurationStorage.setTimeOutProperty("default.preloader.timeout", defaultPreLoaderTimeOut);
        configurationStorage.setTimeOutProperty("default.validation.error.timeout", defaultValidationErrorTimeOut);
    }

    private void setWebDriverBaseUrl() {
        configurationStorage.setProperty("webdriver.base.url", getSystemConfiguration().getBaseUrl());
    }

    private void findStoriesByBranch() {
        String branch = getEnvironmentVariables().getProperty(BRANCH, null);
        if (branch != null) {
            if (branch.startsWith("us-")) {
                findStoriesIn("**/" + branch.replace(".", "_"));
            }
            if (branch.startsWith("sprint-")) {
                findStoriesIn("**/" + branch);
            }
        }
    }

    private void findStoriesByStories() {
        String storyNames = getEnvironmentVariables().getProperty(STORIES, null);
        if (storyNames != null) {
            if (!storyNames.isEmpty()) {
                findStoriesCalled(storyNames);
            }
        }
    }

    private void setThreads() {
        Integer threads = getEnvironmentVariables().getPropertyAsInteger(THREADS, 1);
        configuredEmbedder().embedderControls().useThreads(threads);
    }

    private void setDemoMode() {
        Boolean isDemoModeOn = getEnvironmentVariables().getPropertyAsBoolean(DEMO_MODE, false);
        Storage.getDemoModeConfigurableStorage().setIsDemoModeOn(isDemoModeOn);
    }

    private void parallelExecutionStart() {
        Integer agentPosition
                = getEnvironmentVariables().getPropertyAsInteger("parallel.agent.number", 1);
        Integer agentCount
                = getEnvironmentVariables().getPropertyAsInteger("parallel.agent.total", 1);

        if (!(agentPosition == 1 && agentCount == 1)) {
            List<String> storyPaths = storyPaths();

            failIfAgentIsNotConfiguredCorrectly(agentPosition, agentCount);
            failIfThereAreMoreAgentsThanStories(agentCount, storyPaths.size());

            // The reminder should work out to be either be zero or one.
            int reminder = storyPaths.size() % agentCount;
            int storiesPerAgent = storyPaths.size() / agentCount;

            int startPos = storiesPerAgent * (agentPosition - 1);
            int endPos = startPos + storiesPerAgent;
            if (agentPosition.equals(agentCount)) {
                // In the case of an uneven number the last agent
                // picks up the extra story file.
                endPos += reminder;
            }
            List<String> stories = storyPaths.subList(startPos, endPos);

            outputWhichStoriesAreBeingRun(stories);
            findStoriesCalled(Lambda.join(stories, ";"));
        }
    }

    private void failIfAgentIsNotConfiguredCorrectly(Integer agentPosition,
                                                     Integer agentCount) {
        if (agentPosition == null) {
            throw new RuntimeException("The agent number needs to be specified");
        } else if (agentCount == null) {
            throw new RuntimeException("The agent total needs to be specified");
        } else if (agentPosition < 1) {
            throw new RuntimeException("The agent number is invalid");
        } else if (agentCount < 1) {
            throw new RuntimeException("The agent total is invalid");
        } else if (agentPosition > agentCount) {
            throw new RuntimeException(String.format("There were %d agents in total specified and this agent is outside that range (it is specified as %d)", agentPosition, agentCount));
        }
    }

    private void failIfThereAreMoreAgentsThanStories(Integer agentCount,
                                                     int storyCount) {
        if (storyCount < agentCount) {
            throw new RuntimeException(
                    "There are more agents then there are stories, this agent isn't necessary");
        }
    }

    private void outputWhichStoriesAreBeingRun(List<String> stories) {
        System.out.println("Running stories: ");
        for (String story : stories) {
            System.out.println(" - " + story);
        }
    }

    private void registerWebDriverListener() {
        WebdriverProxyFactory.getFactory().registerListener(new RemoteWebDriverEventListener(getEnvironmentVariables()));
    }
}
