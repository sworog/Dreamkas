package project.lighthouse.autotests.jbehave;

import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.jbehave.ThucydidesJUnitStories;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    public static final String CURRENT_SPRINT = "lighthouse.autotests.sprint";
    public static final String CURRENT_STORY = "lighthouse.autotests.story.name";

	public AcceptanceTestSuite() {

        EnvironmentVariables environmentVariables = getEnvironmentVariables();
        String sprint = environmentVariables.getProperty(CURRENT_SPRINT, null);
        String story = environmentVariables.getProperty(CURRENT_STORY, null);
        if(sprint != null) {
            String storiesPath = "**/sprint".concat(sprint);
            findStoriesIn(storiesPath);
        }
        if (story != null){
            findStoriesCalled(story);
        }
	}
}
