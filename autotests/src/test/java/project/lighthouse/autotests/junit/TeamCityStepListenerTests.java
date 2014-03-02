package project.lighthouse.autotests.junit;

import net.thucydides.core.model.TestOutcome;
import net.thucydides.core.model.TestResult;
import net.thucydides.core.model.TestStep;
import org.junit.Assert;
import org.junit.Test;
import org.mockito.ArgumentCaptor;
import org.slf4j.Logger;
import project.lighthouse.autotests.thucydides.TeamCityStepListener;

import java.util.ArrayList;
import java.util.HashMap;

import static org.mockito.Mockito.*;

/**
 * Test class to test implemented team city thucydides step listener
 */
public class TeamCityStepListenerTests extends Assert {

    private final Logger logger = mock(Logger.class);
    private final TeamCityStepListener teamCityStepListener = new TeamCityStepListener(logger);

    private static final String storyPath = "stories/sprint-1/us-1/1_Scenario.story";

    @Test
    public void scenarioResultIsSuccess() {

        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn(storyPath);
        when(testOutcome.getMethodName()).thenReturn("passedScenario");
        when(testOutcome.isSuccess()).thenReturn(true);

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-1.us-1.1_Scenario.passedScenario']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-1.us-1.1_Scenario.passedScenario']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(2)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(1));
    }

    @Test
    public void scenarioResultIsFailure() {

        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn(storyPath);
        when(testOutcome.getMethodName()).thenReturn("failedScenario");
        when(testOutcome.isFailure()).thenReturn(true);
        when(testOutcome.getTestFailureCause()).thenReturn(new Throwable("the test is failed!"));
        when(testOutcome.getTestSteps()).thenReturn(new ArrayList<TestStep>() {{
            add(0, new TestStep() {{
                setDescription("Failed scenario step");
                setResult(TestResult.FAILURE);
                failedWith(
                        new Throwable(
                            new Throwable("the test is failed!")));
            }});
        }});

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-1.us-1.1_Scenario.failedScenario']";
        String testFailedExpectedMessage = "##teamcity[testFailed  message='the test is failed!' details='Steps:|r|nFailed scenario step (0.0) -> ERROR|r|n|njava.lang.Throwable: the test is failed!|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests$1$1.<init>(TeamCityStepListenerTests.java:58)|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests$1.<init>(TeamCityStepListenerTests.java:55)|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests.scenarioResultIsFailure(TeamCityStepListenerTests.java:54)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)|n\tat sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)|n\tat java.lang.reflect.Method.invoke(Method.java:606)|n\tat org.junit.runners.model.FrameworkMethod$1.runReflectiveCall(FrameworkMethod.java:47)|n\tat org.junit.internal.runners.model.ReflectiveCallable.run(ReflectiveCallable.java:12)|n\tat org.junit.runners.model.FrameworkMethod.invokeExplosively(FrameworkMethod.java:44)|n\tat org.junit.internal.runners.statements.InvokeMethod.evaluate(InvokeMethod.java:17)|n\tat org.junit.runners.ParentRunner.runLeaf(ParentRunner.java:271)|n\tat org.junit.runners.BlockJUnit4ClassRunner.runChild(BlockJUnit4ClassRunner.java:70)|n\tat org.junit.runners.BlockJUnit4ClassRunner.runChild(BlockJUnit4ClassRunner.java:50)|n\tat org.junit.runners.ParentRunner$3.run(ParentRunner.java:238)|n\tat org.junit.runners.ParentRunner$1.schedule(ParentRunner.java:63)|n\tat org.junit.runners.ParentRunner.runChildren(ParentRunner.java:236)|n\tat org.junit.runners.ParentRunner.access$000(ParentRunner.java:53)|n\tat org.junit.runners.ParentRunner$2.evaluate(ParentRunner.java:229)|n\tat org.junit.runners.ParentRunner.run(ParentRunner.java:309)|n\tat org.junit.runner.JUnitCore.run(JUnitCore.java:160)|n\tat com.intellij.junit4.JUnit4IdeaTestRunner.startRunnerWithArgs(JUnit4IdeaTestRunner.java:74)|n\tat com.intellij.rt.execution.junit.JUnitStarter.prepareStreamsAndStart(JUnitStarter.java:202)|n\tat com.intellij.rt.execution.junit.JUnitStarter.main(JUnitStarter.java:65)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)|n\tat sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)|n\tat java.lang.reflect.Method.invoke(Method.java:606)|n\tat com.intellij.rt.execution.application.AppMain.main(AppMain.java:120)|n|r|n|r|n' name='sprint-1.us-1.1_Scenario.failedScenario']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-1.us-1.1_Scenario.failedScenario']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testFailedExpectedMessage, stringArgumentCaptor.getAllValues().get(1));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(2));
    }

    /**
     * This test is failed because listener can't get the children step cause exception
     * Solution: have to iterate through children steps if(isAgroup = true) and print all childSteps info with failed child step
     */
    @Test
    public void scenarioChildStepResultIsFailure() {
        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn(storyPath);
        when(testOutcome.getMethodName()).thenReturn("failedScenario");
        when(testOutcome.isFailure()).thenReturn(true);
        when(testOutcome.getTestFailureCause()).thenReturn(new Throwable("the test is failed!"));
        when(testOutcome.getTestSteps()).thenReturn(new ArrayList<TestStep>() {{
            add(0, new TestStep() {{
                setDescription("Failed scenario step");
                setResult(TestResult.FAILURE);
                addChildStep(new TestStep() {{
                    setDescription("Failed child scenario step");
                    setResult(TestResult.FAILURE);
                    failedWith(
                            new Throwable(
                                    new Throwable("the test is failed!")));
                }});
            }});
        }});

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-1.us-1.1_Scenario.failedScenario']";
        String testFailedExpectedMessage = "##teamcity[testFailed  message='the test is failed!' details='Steps:|r|nFailed scenario step (0.0) -> ERROR|r|n|njava.lang.Throwable: the test is failed!|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests$1$1.<init>(TeamCityStepListenerTests.java:58)|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests$1.<init>(TeamCityStepListenerTests.java:53)|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests.scenarioResultIsFailure(TeamCityStepListenerTests.java:52)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)|n\tat sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)|n\tat java.lang.reflect.Method.invoke(Method.java:606)|n\tat org.junit.runners.model.FrameworkMethod$1.runReflectiveCall(FrameworkMethod.java:47)|n\tat org.junit.internal.runners.model.ReflectiveCallable.run(ReflectiveCallable.java:12)|n\tat org.junit.runners.model.FrameworkMethod.invokeExplosively(FrameworkMethod.java:44)|n\tat org.junit.internal.runners.statements.InvokeMethod.evaluate(InvokeMethod.java:17)|n\tat org.junit.runners.ParentRunner.runLeaf(ParentRunner.java:271)|n\tat org.junit.runners.BlockJUnit4ClassRunner.runChild(BlockJUnit4ClassRunner.java:70)|n\tat org.junit.runners.BlockJUnit4ClassRunner.runChild(BlockJUnit4ClassRunner.java:50)|n\tat org.junit.runners.ParentRunner$3.run(ParentRunner.java:238)|n\tat org.junit.runners.ParentRunner$1.schedule(ParentRunner.java:63)|n\tat org.junit.runners.ParentRunner.runChildren(ParentRunner.java:236)|n\tat org.junit.runners.ParentRunner.access$000(ParentRunner.java:53)|n\tat org.junit.runners.ParentRunner$2.evaluate(ParentRunner.java:229)|n\tat org.junit.runners.ParentRunner.run(ParentRunner.java:309)|n\tat org.junit.runner.JUnitCore.run(JUnitCore.java:160)|n\tat com.intellij.junit4.JUnit4IdeaTestRunner.startRunnerWithArgs(JUnit4IdeaTestRunner.java:74)|n\tat com.intellij.rt.execution.junit.JUnitStarter.prepareStreamsAndStart(JUnitStarter.java:202)|n\tat com.intellij.rt.execution.junit.JUnitStarter.main(JUnitStarter.java:65)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)|n\tat sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)|n\tat java.lang.reflect.Method.invoke(Method.java:606)|n\tat com.intellij.rt.execution.application.AppMain.main(AppMain.java:120)|n|r|n|r|n' name='sprint-1.us-1.1_Scenario.failedScenario']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-1.us-1.1_Scenario.failedScenario']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testFailedExpectedMessage, stringArgumentCaptor.getAllValues().get(1));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(2));
    }

    @Test
    public void scenarioResultIsError() {

        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn(storyPath);
        when(testOutcome.getMethodName()).thenReturn("failedScenario");
        when(testOutcome.isError()).thenReturn(true);
        when(testOutcome.getTestFailureCause()).thenReturn(new Throwable("the test is failed!"));
        when(testOutcome.getTestSteps()).thenReturn(new ArrayList<TestStep>() {{
            add(0, new TestStep() {{
                setDescription("Failed scenario step");
                setResult(TestResult.ERROR);
                failedWith(
                        new Throwable(
                                new Throwable("the test is failed!")));
            }});
        }});

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-1.us-1.1_Scenario.failedScenario']";
        String testFailedExpectedMessage = "##teamcity[testFailed  message='the test is failed!' details='Steps:|r|nFailed scenario step (0.0) -> ERROR|r|n|njava.lang.Throwable: the test is failed!|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests$3$1.<init>(TeamCityStepListenerTests.java:127)|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests$3.<init>(TeamCityStepListenerTests.java:124)|n\tat project.lighthouse.autotests.junit.TeamCityStepListenerTests.scenarioResultIsError(TeamCityStepListenerTests.java:123)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)|n\tat sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)|n\tat java.lang.reflect.Method.invoke(Method.java:606)|n\tat org.junit.runners.model.FrameworkMethod$1.runReflectiveCall(FrameworkMethod.java:47)|n\tat org.junit.internal.runners.model.ReflectiveCallable.run(ReflectiveCallable.java:12)|n\tat org.junit.runners.model.FrameworkMethod.invokeExplosively(FrameworkMethod.java:44)|n\tat org.junit.internal.runners.statements.InvokeMethod.evaluate(InvokeMethod.java:17)|n\tat org.junit.runners.ParentRunner.runLeaf(ParentRunner.java:271)|n\tat org.junit.runners.BlockJUnit4ClassRunner.runChild(BlockJUnit4ClassRunner.java:70)|n\tat org.junit.runners.BlockJUnit4ClassRunner.runChild(BlockJUnit4ClassRunner.java:50)|n\tat org.junit.runners.ParentRunner$3.run(ParentRunner.java:238)|n\tat org.junit.runners.ParentRunner$1.schedule(ParentRunner.java:63)|n\tat org.junit.runners.ParentRunner.runChildren(ParentRunner.java:236)|n\tat org.junit.runners.ParentRunner.access$000(ParentRunner.java:53)|n\tat org.junit.runners.ParentRunner$2.evaluate(ParentRunner.java:229)|n\tat org.junit.runners.ParentRunner.run(ParentRunner.java:309)|n\tat org.junit.runner.JUnitCore.run(JUnitCore.java:160)|n\tat com.intellij.junit4.JUnit4IdeaTestRunner.startRunnerWithArgs(JUnit4IdeaTestRunner.java:74)|n\tat com.intellij.rt.execution.junit.JUnitStarter.prepareStreamsAndStart(JUnitStarter.java:202)|n\tat com.intellij.rt.execution.junit.JUnitStarter.main(JUnitStarter.java:65)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)|n\tat sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)|n\tat sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)|n\tat java.lang.reflect.Method.invoke(Method.java:606)|n\tat com.intellij.rt.execution.application.AppMain.main(AppMain.java:120)|n|r|n|r|n' name='sprint-1.us-1.1_Scenario.failedScenario']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-1.us-1.1_Scenario.failedScenario']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testFailedExpectedMessage, stringArgumentCaptor.getAllValues().get(1));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(2));
    }

    @Test
    public void scenarioResultIsSkipped() {

        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn(storyPath);
        when(testOutcome.getMethodName()).thenReturn("skippedScenario");
        when(testOutcome.isSkipped()).thenReturn(true);

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-1.us-1.1_Scenario.skippedScenario']";
        String testIgnoredExpectedMessage = "##teamcity[testIgnored  name='sprint-1.us-1.1_Scenario.skippedScenario']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-1.us-1.1_Scenario.skippedScenario']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testIgnoredExpectedMessage, stringArgumentCaptor.getAllValues().get(1));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(2));

    }

    @Test
    public void scenarioResultIsPending() {

        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.getPath()).thenReturn(storyPath);
        when(testOutcome.getMethodName()).thenReturn("pendingScenario");
        when(testOutcome.isPending()).thenReturn(true);

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-1.us-1.1_Scenario.pendingScenario']";
        String testIgnoredExpectedMessage = "##teamcity[testIgnored  name='sprint-1.us-1.1_Scenario.pendingScenario']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-1.us-1.1_Scenario.pendingScenario']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testIgnoredExpectedMessage, stringArgumentCaptor.getAllValues().get(1));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(2));
    }

    @Test
    public void testParametrisedWithExampleTableCausesNpeIfGivenStoriesExistInStory() {

        Logger logger = mock(Logger.class);

        TeamCityStepListener teamCityStepListener = new TeamCityStepListener(logger);

        teamCityStepListener.exampleStarted(new HashMap<String, String>() {{
            put("value", "'';!--\"<XSS>=&{()}");
        }});

        TestOutcome testOutcome = getTestOutcome();

        teamCityStepListener.testFinished(testOutcome);

        String testStartedExpectedMessage = "##teamcity[testStarted  name='sprint-29.us-61.XSS_SupplierName_Validation.XSS supplier name validation.{value=|'|';!--\"<XSS>=&{()}}']";
        String testFinishedExpectedMessage = "##teamcity[testFinished  duration='0' name='sprint-29.us-61.XSS_SupplierName_Validation.XSS supplier name validation.{value=|'|';!--\"<XSS>=&{()}}']";

        ArgumentCaptor<String> stringArgumentCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(2)).info(stringArgumentCaptor.capture());
        assertEquals(testStartedExpectedMessage, stringArgumentCaptor.getAllValues().get(0));
        assertEquals(testFinishedExpectedMessage, stringArgumentCaptor.getAllValues().get(1));

    }

    private TestOutcome getTestOutcome() {

        TestOutcome testOutcome = mock(TestOutcome.class);
        when(testOutcome.isDataDriven()).thenReturn(true);
        when(testOutcome.getPath()).thenReturn("stories/sprint-29/us-61/XSS_SupplierName_Validation.story");
        when(testOutcome.getMethodName()).thenReturn("XSS supplier name validation");
        when(testOutcome.getTestSteps()).thenReturn(new ArrayList<TestStep>() {{
            add(0, new TestStep() {{
                setNumber(1);
                setDescription("A scenario that prepares data");
                setResult(TestResult.SUCCESS);
                addChildStep(new TestStep() {{
                    setNumber(2);
                    setDescription("Before scenario");
                    setResult(TestResult.SUCCESS);
                }});
                addChildStep(new TestStep() {{
                    setNumber(3);
                    setDescription("Given the user runs the symfony:env:init command");
                    setResult(TestResult.SUCCESS);
                    addChildStep(new TestStep() {{
                        setNumber(4);
                        setDescription("Run cap auto test symfony env init command");
                        setResult(TestResult.SUCCESS);
                    }});
                }});
            }});
            add(1, new TestStep() {{
                setNumber(5);
                setDescription("Before scenario");
                setResult(TestResult.SUCCESS);
            }});
            add(2, new TestStep() {{
                setNumber(6);
                setDescription("[1] {value='';!--\"<XSS>=&{()}}");
                addChildStep(new TestStep() {{
                    setNumber(7);
                    setDescription("Before scenario");
                    setResult(TestResult.SUCCESS);
                }});
                addChildStep(new TestStep() {{
                    setNumber(8);
                    setDescription("Given the user opens supplier create page [Open supplier create page]");
                    setResult(TestResult.SUCCESS);
                }});

            }});
        }});
        return testOutcome;
    }

    @Test
    public void testEscapingProperty() {
        String expectedMessage =
                "##teamcity[testFailed  message='A JSONObject text must begin with |'{|' at character 1|'. Json: |'null|'. Url: |'http://i2-firefox-us-60.autotests.api.lighthouse.pro/oauth/v2/token?grant_type=password&username=administrator&password=lighthouse&client_id=autotests_autotests&client_secret=secret|'. Response: |'HTTP/1.1 502 Bad Gateway |[Server: nginx/1.1.19, Date: Wed, 26 Feb 2014 09:21:10 GMT, Content-Type: text/html, Content-Length: 173, Connection: keep-alive|]|'. Response message: |'<html>|n<head><title>502 Bad Gateway</title></head>|n<body bgcolor=\"white\">|n<center><h1>502 Bad Gateway</h1></center>|n<hr><center>nginx/1.1.19</center>|n</body>|n</html>' details='Steps:|r|n|r|n' name='sprint-29.us-60.60_Simple_Supplier_Create.test']";

        Logger logger = mock(Logger.class);
        TeamCityStepListener teamCityStepListener = new TeamCityStepListener(logger);

        TestOutcome testOutcome = getTestOutComeForEscapingPropertyTest();
        teamCityStepListener.testFinished(testOutcome);

        ArgumentCaptor<String> outPutCaptor = ArgumentCaptor.forClass(String.class);
        verify(logger, times(3)).info(outPutCaptor.capture());
        assertEquals(expectedMessage, outPutCaptor.getAllValues().get(1));
    }

    private TestOutcome getTestOutComeForEscapingPropertyTest() {
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
