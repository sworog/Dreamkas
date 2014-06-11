package project.lighthouse.autotests.console.backend;

public class SymfonyUserCreateCommand extends BackendCommand {

    public SymfonyUserCreateCommand(String email, String password) {
        super(String.format("symfony:user:create -S email=%s -S userpass=%s", email, password));
    }
}
