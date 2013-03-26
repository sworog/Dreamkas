package project.lighthouse.autotests.jbehave;

import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.jbehave.ThucydidesJUnitStories;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    public static final String CURRENT_SPRINT = "lighthouse.autotests.sprint";
    public static final String CURRENT_STORY = "lighthouse.autotests.story.name";
    public static final String CURRENT_BRANCH = "lighthouse.autotests.branch";

	public AcceptanceTestSuite() {

        EnvironmentVariables environmentVariables = getEnvironmentVariables();
        String branch = environmentVariables.getProperty(CURRENT_BRANCH, null);
        if(branch != null){
            if(branch.startsWith("us-")){
                findStoriesCalled(branch.substring(3));
            }
            if(branch.startsWith("sprint-")){
                findStoriesIn("**/" + branch);
            }
        }
	}
}
