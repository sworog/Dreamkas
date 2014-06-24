package project.lighthouse.autotests.steps.email;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.handler.email.EmailHandler;
import project.lighthouse.autotests.handler.email.EmailMessage;

import javax.mail.MessagingException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class EmailSteps extends ScenarioSteps {

    private EmailMessage emailMessage;

    private static final String SIGN_UP_REGEX_PATTERN = "Добро пожаловать в Lighthouse!\r\n\r\nВаш пароль для входа: (.*)\r\nЕсли это письмо пришло вам по ошибке, просто проигнорируйте его.\r\n";
    private static final String RESTORE_PASSWORD_REGEX_PATTERN = "Вы воспользовались формой восстановления пароля в Lighthouse.\r\n\r\nВаш новый пароль для входа: (.*)\r\n-----\r\nLighthouse.pro\r\n";

    @Step
    public void deleteAllMessages() {
        new EmailHandler().deleteAllMessages();
    }

    @Step
    public void getEmailMessage() throws MessagingException, InterruptedException {
        emailMessage = new EmailHandler().getEmailMessage();
    }

    @Step
    public void assertEmailMessageFrom(String expectedFrom) {
        assertThat(emailMessage.getFrom(), is(expectedFrom));
    }

    @Step
    public void assertEmailMessageSubject(String expectedSubject) {
        assertThat(emailMessage.getSubject(), is(expectedSubject));
    }

    @Step
    public void matchEmailMessageContentToSignUpRegexPattern() {
        assertThat(getExceptionMessage(), true, is(getSignUpEmailMatcher().matches()));
    }

    @Step
    public void matchEmailContentToRestorePasswordRegexPattern() {
        assertThat(getExceptionMessage(), true, is(getRestorePasswordEmailMatcher().matches()));
    }

    @Step
    public String getRestorePasswordEmailCredentials() {
        return getEmailCredentials(getRestorePasswordEmailMatcher());
    }

    @Step
    public String getSignUpEmailCredentials() {
        return getEmailCredentials(getSignUpEmailMatcher());
    }

    private String getEmailCredentials(Matcher emailMatcher) {
        if (emailMatcher.matches()) {
            return emailMatcher.group(1);
        } else {
            String message = String.format("Cannot get password from the email message because email content is not matching. email content: '%s'", emailMessage.getContent());
            throw new AssertionError(message);
        }
    }

    private Matcher getSignUpEmailMatcher() {
        return getEmailMatcher(SIGN_UP_REGEX_PATTERN);
    }

    private Matcher getRestorePasswordEmailMatcher() {
        return getEmailMatcher(RESTORE_PASSWORD_REGEX_PATTERN);
    }

    private Matcher getEmailMatcher(String regexPattern) {
        Pattern pattern = Pattern.compile(regexPattern);
        return pattern.matcher(emailMessage.getContent());
    }

    private String getExceptionMessage() {
        return String.format("The email message content is not matching the template. email message content: '%s'", emailMessage.getContent());
    }
}
