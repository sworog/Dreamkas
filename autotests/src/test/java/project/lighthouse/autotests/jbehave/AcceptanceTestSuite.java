package project.lighthouse.autotests.jbehave;

import net.thucydides.core.steps.ScenarioSteps;
import net.thucydides.jbehave.ThucydidesJUnitStories;
import org.slf4j.LoggerFactory;
import org.slf4j.Logger;

import java.nio.charset.Charset;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {

    private static final Logger LOGGER = LoggerFactory.getLogger(AcceptanceTestSuite.class);

	public AcceptanceTestSuite() {
        LOGGER.info(Charset.defaultCharset().name());
		findStoriesIn("**/Actual/");		
	}
}
