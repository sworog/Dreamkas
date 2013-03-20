package project.lighthouse.autotests.jbehave;

import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.jbehave.ThucydidesJUnitStories;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    public static String CURRENT_SPRINT = "lighthouse.autotests.sprint";

	public AcceptanceTestSuite() {
        //findStoriesIn("**/Actual/");
        EnvironmentVariables environmentVariables = getEnvironmentVariables();
        String sprint = environmentVariables.getProperty(CURRENT_SPRINT, null);
        if(sprint != null) {
            String storiesPath = "**/sprint".concat(sprint);
            findStoriesIn(storiesPath);
        }
	}
}
