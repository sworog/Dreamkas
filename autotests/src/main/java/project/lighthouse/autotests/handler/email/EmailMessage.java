package project.lighthouse.autotests.handler.email;

/**
 * Class to store email message from, subject and content values
 */
public class EmailMessage {

    private String from;
    private String subject;
    private String content;

    public EmailMessage(String from, String subject, String content) {
        this.from = from;
        this.subject = subject;
        this.content = content;
    }

    public String getFrom() {
        return from;
    }

    public String getSubject() {
        return subject;
    }

    public String getContent() {
        return content;
    }
}
