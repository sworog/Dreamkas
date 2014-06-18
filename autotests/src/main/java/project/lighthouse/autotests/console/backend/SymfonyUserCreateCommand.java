package project.lighthouse.autotests.console.backend;

import project.lighthouse.autotests.helper.UUIDGenerator;
import project.lighthouse.autotests.storage.Storage;

/**
 * symfony:user:create cap command implementation
 */
public class SymfonyUserCreateCommand extends BackendCommand {

    public SymfonyUserCreateCommand(String email, String password, String customProjectName) {
        super(String.format("symfony:user:create -S email=%s -S userpass=%s -S customProjectName=%s", email, password, customProjectName));
    }

    public SymfonyUserCreateCommand(String email, String password) {
        this(email, password, userProjectId());
    }

    private static String userProjectId() {
        String projectUUID = UUIDGenerator.generateWithoutHyphens();
        Storage.getUserVariableStorage().setUserProjectName(projectUUID);
        return projectUUID;
    }
}
