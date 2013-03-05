package project.lighthouse.autotests.jbehave;

import net.thucydides.jbehave.ThucydidesJUnitStories;
import org.slf4j.LoggerFactory;

import java.nio.charset.Charset;

public class AcceptanceTestSuite extends ThucydidesJUnitStories {
	public AcceptanceTestSuite() {
        LoggerFactory.getLogger(AcceptanceTestSuite.class).info(Charset.defaultCharset().toString());
        findStoriesIn("**/Actual/");
	}
}
