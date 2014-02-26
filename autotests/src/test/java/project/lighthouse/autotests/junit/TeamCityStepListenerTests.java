package project.lighthouse.autotests.junit;

import net.thucydides.core.model.TestOutcome;
import org.junit.Assert;
import org.junit.Test;
import org.mockito.ArgumentCaptor;
import org.slf4j.Logger;
import project.lighthouse.autotests.thucydides.TeamCityStepListener;

import static org.mockito.Mockito.*;

public class TeamCityStepListenerTests extends Assert {

    @Test
    public void testEscapingProperty() {
        String expectedMessage =
                "##teamcity[testFailed  message='A JSONObject text must begin with |'{|' at character 1|'. Json: |'null|'. Url: |'http://i2-firefox-us-60.autotests.api.lighthouse.pro/oauth/v2/token?grant_type=password&username=administrator&password=lighthouse&client_id=autotests_autotests&client_secret=secret|'. Response: |'HTTP/1.1 502 Bad Gateway |[Server: nginx/1.1.19, Date: Wed, 26 Feb 2014 09:21:10 GMT, Content-Type: text/html, Content-Length: 173, Connection: keep-alive|]|'. Response message: |'<html>|n<head><title>502 Bad Gateway</title></head>|n<body bgcolor=\"white\">|n<center><h1>502 Bad Gateway</h1></center>|n<hr><center>nginx/1.1.19</center>|n</body>|n</html>' details='Steps:|r|n|r|n' name='sprint-29.us-60.60_Simple_Supplier_Create.test']";

        Logger logger = mock(Logger.class);
        TeamCityStepListener teamCityStepListener = new TeamCityStepListener(logger);

        TestOutcome testOutcome = getTestOutCome();
        teamCityStepListener.testFinished(testOutcome);

        ArgumentCaptor<String> outPutCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(outPutCaptor.capture());
        assertEquals(expectedMessage, outPutCaptor.getAllValues().get(1));
    }

    private TestOutcome getTestOutCome() {
        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn("stories/sprint-29/us-60/60_Simple_Supplier_Create.story");
        when(testOutcome.getMethodName()).thenReturn("test");
        when(testOutcome.isFailure()).thenReturn(true);
        when(testOutcome.getTestFailureCause()).thenReturn(new Throwable(exceptionMessage()));
        return testOutcome;
    }

    private String exceptionMessage() {
        return "A JSONObject text must begin with '{' at character 1'. Json: 'null'. Url: 'http://i2-firefox-us-60.autotests.api.lighthouse.pro/oauth/v2/token?grant_type=password&username=administrator&password=lighthouse&client_id=autotests_autotests&client_secret=secret'. Response: 'HTTP/1.1 502 Bad Gateway [Server: nginx/1.1.19, Date: Wed, 26 Feb 2014 09:21:10 GMT, Content-Type: text/html, Content-Length: 173, Connection: keep-alive]'. Response message: '<html>\n" +
                "<head><title>502 Bad Gateway</title></head>\n" +
                "<body bgcolor=\"white\">\n" +
                "<center><h1>502 Bad Gateway</h1></center>\n" +
                "<hr><center>nginx/1.1.19</center>\n" +
                "</body>\n" +
                "</html>";
    }
}
