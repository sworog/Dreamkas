package ru.dreamkas.storage.containers.user;

import java.util.ArrayList;

public class UserContainerList extends ArrayList<UserContainer> {

    public UserContainer getContainerWithEmail(String email) {
        for (UserContainer userContainer : this) {
            if (userContainer.getEmail().equals(email)) {
                return userContainer;
            }
        }
        String message = String.format("No such user container with email '%s'", email);
        throw new AssertionError(message);
    }

    public Boolean hasContainerWithEmail(String email) {
        for (UserContainer userContainer : this) {
            if (userContainer.getEmail().equals(email)) {
                return true;
            }
        }
        return false;
    }
}
